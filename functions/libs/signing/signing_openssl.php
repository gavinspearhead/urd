<?php

class Services_Signing_Openssl extends Services_Signing_Base {
	/*
	 * Public key cache prevent us to acually decode and call OpenSSL
	 * to create an OpenSSL handle to the public key, saving CPU cycles
	 */
	private $_pubKeyCache = array();

	/* 
	 * Override visibility of the constructor see GH issue #1554
	 */	
	public function __construct() {
	} # ctor

	/*
	 * Actually validates the RSA signature
	 */
	protected function checkRsaSignature($toCheck, $signature, $rsaKey, $useCache) {
		# First decode the signature
		$signature = base64_decode($signature);

		if (isset($this->_pubKeyCache[$rsaKey['modulo'] . $rsaKey['exponent']])) {
			$openSslPubKey = $this->_pubKeyCache[$rsaKey['modulo'] . $rsaKey['exponent']];
			$verified = openssl_verify($toCheck, $signature, $openSslPubKey);
		} else {
			# Initialize the public key to verify with
			$pubKey['n'] = base64_decode($rsaKey['modulo']);
			$pubKey['e'] = base64_decode($rsaKey['exponent']);

			$openSslPubKey = openssl_pkey_get_public($this->seclibToOpenSsl($pubKey));
			$verified = openssl_verify($toCheck, $signature, $openSslPubKey);
			
			# Keep caching the resource?
			if ($useCache) {
				$this->_pubKeyCache[$rsaKey['modulo'] . $rsaKey['exponent']] = $openSslPubKey;
			} else {
				openssl_free_key($openSslPubKey);
			} # else
		} # else
		
		return $verified;
	} # checkRsaSignature

	/*
	 * Creates a public and private key
	 */
	public function createPrivateKey($sslCnfPath) {
		$rsa = new Crypt_RSA();
		$rsa->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1);
			
		$opensslPrivKey = openssl_pkey_new(array('private_key_bits' => 1024, 'config' => realpath($sslCnfPath)));
		openssl_pkey_export($opensslPrivKey, $privateKey, null, array('config' => realpath($sslCnfPath)));
		$publicKey = openssl_pkey_get_details($opensslPrivKey);
		$publicKey = $publicKey['key'];
		openssl_free_key($opensslPrivKey);

		return array('public' => $publicKey,
					 'private' => $privateKey);
	} # createPrivateKey

	/*
	 * Helper function for parsing ASN.1
	 */
	function _getOidElementLength($component) {
		# Code copied from
		#   http://chaosinmotion.com/wiki/index.php?title=ASN.1_Library
        if ($component < 0) return 10;               // Full 64 bits takes 10*7 bits to encode
        $l = 1;
        for ($i = 1; $i < 9; ++$i) {
            $l <<= 7;
            if ($component < $l) break;
        }
        return $i;
	}

	/*
	 * Helper function for parsing ASN.1
	 */
	function _encodeObjectId($vals) {
		$return = array();
		
		$return[] = 40 * $vals[0] + $vals[1];
		$valCount = count($vals);
		for($i = 2; $i < $valCount; $i++) {
			# Code copied from
			#   http://chaosinmotion.com/wiki/index.php?title=ASN.1_Library
			$v = $vals[$i];
			$len = $this->_getOIDElementLength($v);
			
			for ($j = $len-1; $j > 0; --$j) {
				$m = 0x0080 | (0x007F & ($v >> ($j * 7)));
				$return[] = (int) $m;
			}
			$return[] = (int)(0x007F & $v);
		}

		return $return;
	} # _encodeObjectId


	/*
	 * Helper function for parsing ASN.1
	 */
	function seclibToOpenSsl($pubKey) {
		/* 
		 * Structuur van de OpenSSL publickey is als volgt:
		 *
		 * - Sequence
		 * +- Sequence
		 * ++- Object identifier die de RSA key weergeeft (1.2.840.113549.1.1.1)
		 * ++- NULL
		 * +- Bit String
		 * ++- Sequence
		 * +++- Integer
		 * +++- Integer
		 *
		 * Dit willen we nabootsen met deze encoding
		 */
		$publicExponent = $pubKey['e'];
		$modulus = $pubKey['n'];
		$components = array(
			'modulus' => pack('Ca*a*', CRYPT_RSA_ASN1_INTEGER, $this->_encodeLength(strlen($modulus)), $modulus),
			'publicExponent' => pack('Ca*a*', CRYPT_RSA_ASN1_INTEGER, $this->_encodeLength(strlen($publicExponent)), $publicExponent)
		);

		/* 
		 * First encoden we de keys in een bitstring 
		 */		 
		$encodedKeys = pack('Ca*a*a*',
					CRYPT_RSA_ASN1_SEQUENCE, 		 # Sequence
					$this->_encodeLength(strlen($components['modulus']) + strlen($components['publicExponent'])),
					$components['modulus'], 
					$components['publicExponent']
        );
		$encodedKeys = pack('Ca*Ca*',
					0x03, 		# 0x03 means BIT STRING
					$this->_encodeLength(strlen($encodedKeys) + 1), # add 1 voor de 0 unused bits
					0,
					$encodedKeys
		);
		
		/*
		 * Nu creeeren we de type header
		 *
		 * We kunnen de rsaIdentifier berekenen, maar omdat dat toch nooit verandert, 
		 * zetten we de berekening klaar. 
		 * Code om te berekenen:
		 *      $rsaIdentifier = $this->_encodeObjectId(array(1,2,840,113549,1,1,1)); 	// Magic value of RSA
		 *
		 *		$encryptionType = pack('Ca*',
		 *				0x06,		# ASN.1 OBJECT IDENTIFIER
		 *				$this->_encodeLength(count($rsaIdentifier))
		 *		);
		 *		$rsaIdentifierCount = count($rsaIdentifier);
		 *		for($i = 0; $i < $rsaIdentifierCount; $i++) {	
		 *			$encryptionType .= chr($rsaIdentifier[$i]);
		 *		} # foreach
		 *
		 *
		 *		# de encryption type header wordt geappend met een ASN.1 NULL
		 * 		$encryptionType .= pack('CC',
		 *					0x05,			# ASN.1 NULL
		 *					0
		 *		);
		 *
		 *		# en de encryptiontype pakken we in in een sequence
		 *		$encryptionType = pack('Ca*a*',
		 *			CRYPT_RSA_ASN1_SEQUENCE, 		 # Sequence
		 *			$this->_encodeLength(strlen($encryptionType)),
		 *			$encryptionType
		 *		);
		 */
		$encryptionType = "\x30\xd\x6\x9\x2a\x86\x48\x86\xf7\xd\x1\x1\x1\x5\x0";
		
		# en ook dit alles pakken we in een sequence in
		$endResult = pack('Ca*a*',
					CRYPT_RSA_ASN1_SEQUENCE, 		 # Sequence
					$this->_encodeLength(15 + strlen($encodedKeys)), # 15 == strlen($encryptionType)
					$encryptionType . $encodedKeys
		);
		
		return "-----BEGIN PUBLIC KEY-----\n" . 
				chunk_split(base64_encode($endResult), 64) .
				"-----END PUBLIC KEY-----\n";
	} # seclibToOpenSsl

    /**
	 *
	 * From phpSeclib library
	 *
     * DER-encode the length
     *
     * DER supports lengths up to (2**8)**127, however, we'll only support lengths up to (2**8)**4.  See
     * {@link http://itu.int/ITU-T/studygroups/com17/languages/X.690-0207.pdf#p=13 X.690 § 8.1.3} for more information.
     *
     * @access private
     * @param Integer $length
     * @return String
     */
    function _encodeLength($length)
    {
        if ($length <= 0x7F) {
            return chr($length);
        }

        $temp = ltrim(pack('N', $length), chr(0));
        return pack('Ca*', 0x80 | strlen($temp), $temp);
    }
	
} # Services_Signing_Openssl

