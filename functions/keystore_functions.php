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

$pathf = realpath(dirname(__FILE__));

class keystore {
    const keystore_file = '.keystore.php';
    static function decrypt_password(DatabaseConnection $db, $password)
    {
        if (substr($password, 0, 5) == ':ENC:') {
            global $pathf;
            $default_keystore_path = "$pathf/../";
            $keystore_path = get_config($db, 'keystore_path', $default_keystore_path) . self::keystore_file;
            $rv = @include $keystore_path; 
            if ($rv === FALSE) {
                throw new exception('Keystore not found: ' . $keystore_path);
            }
            if (!isset($encryption_key) ) { 
                throw new exception('No valid encryption key found');
            }

            $password = substr($password, 5);
            $iv = base64_decode(substr($password, 0, strpos($password, ':')));

            $enc_pw = base64_decode(substr($password, strpos($password, ':') + 1));
            $dec_pw = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $enc_pw, MCRYPT_MODE_CBC, $iv);
            $encryption_key = '';
            unset($encryption_key);
            $dec_pw = rtrim($dec_pw, "\0");

            return $dec_pw;
        } else {
            return $password;
        }
    }

    static function encrypt_password(DatabaseConnection $db, $password)
    {
        if (substr($password, 0, 5) == ':ENC:') {
            return $passord; // already encrypted; don't double encrypt!:w
        }
        global $pathf;
        $default_keystore_path = "$pathf/../";
        $keystore_path = get_config($db, 'keystore_path', $default_keystore_path) . self::keystore_file;
        $rv = @include $keystore_path; 
        if ($rv === FALSE) {
            throw new exception('Keystore not found: ' . $keystore_path);
        }
        if (!isset($encryption_key)) { 
            throw new exception('No valid encryption key found');
        }

        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $enc_pw = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, $password, MCRYPT_MODE_CBC, $iv);
        $encryption_key = '';
        unset($encryption_key);

        return ':ENC:' . base64_encode($iv) . ':' . base64_encode($enc_pw);
    }


    static function create_keystore(DatabaseConnection $db, $encryption_key = '')
    {
        global $pathf;
        $default_keystore_path = "$pathf/../"; 
        $keystore_path = get_config($db, 'keystore_path', $default_keystore_path);
        $keystore_file = $keystore_path . self::keystore_file;
        $rv = @include $keystore_file; 

        if (file_exists($keystore_file)) {
            throw new exception('A keystore file already exists', 1);
        }
        if (!is_writable($keystore_path)) {
            throw new exception('The directory of for the keystore is not writeable', 1);
        }
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
        $rv = chmod($keystore_file, 0440);
        if ($rv === FALSE) {
            throw new exception('Could not chmod keystore file', 0);
        }

        return TRUE;
    }

    static function verify_keystore(DatabaseConnection $db)
    {
        global $pathf;
        if (get_config($db, 'use_encrypted_passwords') == 0) {
            return TRUE;
        }
        $default_keystore_path = "$pathf/../"; 
        $keystore_path = get_config($db, 'keystore_path', $default_keystore_path) . self::keystore_file;
        clearstatcache();
        if (! file_exists($keystore_path)) {
            throw new exception('Keystore file not found: ' . $keystore_path . 'run .urdd.sh -k to create a key store');
        }
        if (! is_readable($keystore_path)) {
            throw new exception('Keystore file not readable: Run chmod 0440 ' . $keystore_path);
        }
        $rv = @include $keystore_path;
        if (!isset($encryption_key) || strlen ($encryption_key) < 16) { 
            throw new exception('No valid encryption key found');
        }
        if (fileperms($keystore_path) & 0337 != 0) {
            write_log('Permission of keystore may not be secure. Run chmod 0440 ' . $keystore_path , LOG_WARNING);
        }

    }
}
