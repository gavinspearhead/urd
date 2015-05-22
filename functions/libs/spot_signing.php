<?php

$pathsps = realpath(dirname(__FILE__));

require_once "$pathsps/biginteger.php";
require_once "$pathsps/rsa.php";

class spotsigning
{
    public function __construct($use_openssl)
    {
        if (($use_openssl) && (!defined('CRYPT_RSA_MODE'))) {
            define('CRYPT_RSA_MODE', CRYPT_RSA_MODE_OPENSSL);
        }
    }

    private function checkRsaSignature($toCheck, $signature, $rsaKey)
    {
        # de signature is base64 encoded, eerst decoden
        $signature = base64_decode($signature);

        # Initialize the public key to verify with
        $pubKey['n'] = new Math_BigInteger(base64_decode($rsaKey['modulo']), 256);
        $pubKey['e'] = new Math_BigInteger(base64_decode($rsaKey['exponent']), 256);

        # and verify the signature
        $rsa = new Crypt_RSA();
        $rsa->loadKey($pubKey, CRYPT_RSA_PUBLIC_FORMAT_RAW);
        $rsa->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1);

        # Supress notice if the signature was invalid
        $saveErrorReporting = error_reporting(E_ERROR);
        $tmpSave = $rsa->verify($toCheck, $signature);
        error_reporting($saveErrorReporting);

        return $tmpSave;
    }

    /*
     * RSA signed een bericht, en geeft alle componenten terug
     * die nodig zijn om dit te valideren, dus:
     *
     * - base64 encoded signature (signature)
     * - Public key (publickey)
     * - Het bericht dat gesigned is (message)
     */
    public function signMessage($privatekey, $message)
    {
        /**
         * Test code:
         *
         * $rsa->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1);
         * extract($rsa->createKey());
         * $spotSigning = new SpotSigning();
         * $x = $spotSigning->signMessage($privatekey, 'testmessage');
         *
         */

        $rsa = new Crypt_RSA();
        $rsa->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1);
        $rsa->loadKey($privatekey);

        # extract de public key
        $signature = $rsa->sign($message);
        $publickey = $rsa->getPublicKey(CRYPT_RSA_PUBLIC_FORMAT_RAW);

        return array('signature' => base64_encode($signature),
                     'publickey' => array('modulo' => base64_encode($publickey['n']->toBytes()), 'exponent' => base64_encode($publickey['e']->toBytes())),
                     'message' => $message);
    }

    /*
     * Converteer een voor ons bruikbare publickey, naar een publickey
     * formaat gebruikt door de SpotNet native client
     */
    public function pubkeyToXml($pubkey)
    {
        return '<RSAKeyValue><Modulus>' . $pubkey['n'] . '</Modulus><Exponent>' . $pubkey['e'] . '</Exponent></RSAKeyValue>';
    }

    /*
     * Helper functie om een spot header (resultaat uit een xover of getHeader()) te verifieeren
     */
    public function verifySpotHeader($spot, $signature, $rsakeys)
    {
        # This is the string to verify
        $toCheck = $spot['title'] . substr($spot['header'], 0, strlen($spot['header']) - strlen($spot['headersign']) - 1) . $spot['poster'];

        # Check the RSA signature on the spot

        return $this->checkRsaSignature($toCheck, $signature, $rsakeys[$spot['keyid']]);
    }

    /*
     * Helper functie om een fullspot te verifieeren
     */
    public function verifyFullSpot($spot)
    {
        if ((empty($spot['user-signature'])) || (empty($spot['user-key']))) {
            return FALSE;
        }

        $verified = $this->checkRsaSignature('<' . $spot['messageid'] . '>', $spot['user-signature'], $spot['user-key']);
        if (!$verified) {
            $verified = $this->checkRsaSignature($spot['xml-signature'], $spot['user-signature'], $spot['user-key']);
        }

        return $verified;
    }

    /*
     * Helper functie om een comment header te verifieeren
     */
    public function verifyComment($comment)
    {
        $verified = FALSE;
        if ((!empty($comment['user-signature'])) && (!empty($comment['user-key']))) {
            $verified = $this->checkRsaSignature('<' . $comment['messageid'] . '>', $comment['user-signature'], $comment['user-key']);
            if (!$verified) {
                $verified = $this->checkRsaSignature(
                    '<' . $comment['messageid'] . '>' . implode("\r\n", $comment['body']) . "\r\n" . $comment['from'],
                    $comment['user-signature'],
                    $comment['user-key']);
            }
        }

        # als een sport qua RSA signature al klopt, kunnen we ook nog controleren op de users'
        # hash, deze zou eigenlijk ook moeten kloppen.
        # Deze hash is puur gemaakt om rekenkracht te vereisen aan de kant van de poster om
        # eventuele floods te voorkomen, de hash is dus ook op zich door iedereen te creeeren.
        #
        if ($verified) {
            # $userSignedHash = sha1('<' . $comment['messageid'] . '>', false);
            # $verified = (substr($userSignedHash, 0, 3) == '0000');
        }

        return $verified;
    }
}
