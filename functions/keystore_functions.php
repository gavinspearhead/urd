<?php
/*
 *  This file is part of Urd.
 *  vim:ts=4:expandtab:cindent
 *
 *  Urd is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  Urd is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program. See the file "COPYING". If it does not
 *  exist, see <http://www.gnu.org/licenses/>.
 *
 * $LastChangedDate: 2013-09-11 00:48:12 +0200 (wo, 11 sep 2013) $
 * $Rev: 2925 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: keystore_functions.php 2925 2013-09-10 22:48:12Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

class keystore
{
    const keystore_file = '.keystore.php';
    const cipher = "BF-CBC";

    private static function get_keystore_path(DatabaseConnection $db)
    {
            global $pathf;
            $default_keystore_path = $pathf . DIRECTORY_SEPARATOR . '..';
            add_dir_separator($default_keystore_path);
            $keystore_path = get_config($db, 'keystore_path', $default_keystore_path);
            add_dir_separator($keystore_path);
///            var_dump($keystore_path);
            return $keystore_path;
    }

    private static function get_keystore_file(DatabaseConnection $db)
    {
        $keystore_path = self::get_keystore_path($db);

        return $keystore_path . self::keystore_file;
    }

    public static function decrypt_password(DatabaseConnection $db, $password)
    {
        if (substr($password, 0, 5) == ':ENC:') {
            $keystore_file = self::get_keystore_file($db);
            $rv = @include $keystore_file;
            if ($rv === FALSE) {
                throw new exception('Keystore not found: ' . $keystore_file);
            }
            if (!isset($encryption_key) ) {
                throw new exception('No valid encryption key found');
            }

            $password = substr($password, 5);
            $iv = base64_decode(substr($password, 0, strpos($password, ':')));

            $enc_pw = base64_decode(substr($password, strpos($password, ':') + 1));
            #$dec_pw = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $enc_pw, MCRYPT_MODE_CBC, $iv);
            $dec_pw = openssl_decrypt($enc_pw, self::cipher, $encryption_key, OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING, $iv);
            if ($dec_pw === FALSE) {
                throw new exception('decryption of password failed');
            }
            $encryption_key = '';
            unset($encryption_key);
            $dec_pw = rtrim($dec_pw, "\0");
            return $dec_pw;
        } else {
            return $password;
        }
    }

    public static function encrypt_password(DatabaseConnection $db, $password)
    {
        if (substr($password, 0, 5) == ':ENC:') {
            return $passord; // already encrypted; don't double encrypt!
        }
        $keystore_file = self::get_keystore_file($db);
        $rv = @include $keystore_file;
        if ($rv === FALSE) {
            throw new exception('Keystore file not found: ' . $keystore_file);
        }
        if (!isset($encryption_key)) {
            throw new exception('No valid encryption key found');
        }

#       $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
        $iv_size = openssl_cipher_iv_length(self::cipher);
#$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $iv = openssl_random_pseudo_bytes($iv_size);
        if ($m = strlen($password)% $iv_size) {
            $password .= str_repeat("\x00",  $iv_size - $m);
        }
#        $enc_pw = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, $password, MCRYPT_MODE_CBC, $iv);
        $enc_pw = openssl_encrypt($password, self::cipher, $encryption_key,OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING, $iv);
        //var_dump($enc_pw, $password);
        if ($enc_pw === FALSE) {
            throw new exception('encryption of password failed ' . openssl_error_string ());
        }
        $encryption_key = '';
        unset($encryption_key);

        return ':ENC:' . base64_encode($iv) . ':' . base64_encode($enc_pw);
    }

    public static function create_keystore(DatabaseConnection $db, $encryption_key = '', $reuse=TRUE)
    {
        global $pathf;
        $keystore_file = self::get_keystore_file($db);
        $keystore_path = self::get_keystore_path($db);
        $rv = @include $keystore_file;
        if (!is_writable($keystore_path)) {
            throw new exception('The directory for the keystore is not writeable', 1);
        }

        if (file_exists($keystore_file)) {
            if (!$reuse) {
                throw new exception('A keystore file already exists', 1);
            }
            self::verify_keystore($db, TRUE);
        } else {
            $rv = touch($keystore_file);
            if ($rv === FALSE) {
                throw new exception('Could not create keystore file', 0);
            }

            if ($encryption_key == '') {
                $encryption_key = generate_password(32);
            }

            $rv = file_put_contents($keystore_file, "<?php\n\$encryption_key='$encryption_key';\n");
            unset($encryption_key);
            if ($rv === FALSE) {
                throw new exception('Could not write data to keystore file', 0);
            }
        }
        $rv = chmod($keystore_file, 0440);
        if ($rv === FALSE) {
            throw new exception('Could not chmod keystore file', 0);
        }

        return TRUE;
    }

    public static function verify_keystore(DatabaseConnection $db, $FORCE_CHECK=FALSE)
    {
        if (get_config($db, 'use_encrypted_passwords') == 0 && $FORCE_CHECK !== FALSE) {
            return TRUE;
        }
        $keystore_file = self::get_keystore_file($db);
        clearstatcache();
        if (!file_exists($keystore_file)) {
            throw new exception('Keystore file not found: ' . $keystore_file . 'run .urdd.sh -k to create a key store');
        }
        if (!is_readable($keystore_file)) {
            $grp = posix_getgid();
            $grp_info = posix_getgrgid($grp);
            $grp_name = $grp_info['name'];
            throw new exception('Keystore file not readable: Run "chmod 0440 ' . $keystore_file . '; chgrp ' . $grp_name . ' ' . $keystore_file . '"');
        }
        $rv = @include $keystore_file;
        if (!isset($encryption_key) || strlen ($encryption_key) < 16) {
            throw new exception('No valid encryption key found');
        }
        if (fileperms($keystore_file) & 0337 != 0) {
            write_log('Permission of keystore file may not be secure. Run chmod 0440 ' . $keystore_file, LOG_WARNING);
        }
    }
}
