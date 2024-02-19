<?php
	header('Content-Type:application/json; charset=utf-8');
	$content = file_get_contents('php://input');
	$api = 'http://196.188.120.3:11443/service-openup/toTradeWebPay';
	$appkey = '835ea4bee622439a942e9566e24dcc60';
	$publicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjJs34fJhR1iSlaVSgluGmn7n8AlmjRefXXYHTlBj/IGsaUISJb5TIiZ0lt4y9WphdKC7v0qEPV7mFLKwUHHOPNBetbym90T8UWj7kU5CERyJlrqXSdBSJu1pFuFKScQpflOE2bJfFFiXrXYpSjEV9rFwOsuDCZbOPlcqe+Rg5VcZuIYvtNk2HnQS3v0wiRUnGPHqcOUxUuIDMN5lVLcaPXIPLdnCoPLqIWbSgxaTx/yjBkitnQ0D2R45Wo9dYZ5cw/OGYQO/L7KKRaM8HERmJIemzpHJSe7IFUAMg+M6PlpfoZUP2j/OSMZnMSqeOaY1VUNzIMWcHSQXgGShBR3F0QIDAQAB
';
	$nofityData = decryptRSA($content, $publicKey);
	
	echo '{"code":0,"msg":"success"}';
	
	function decryptRSA($source, $key) {
		$pubPem = chunk_split($key, 64, "\n");
		$pubPem = "-----BEGIN PUBLIC KEY-----\n" . $pubPem . "-----END PUBLIC KEY-----\n";
		$public_key = openssl_pkey_get_public($pubPem); 
		if(!$public_key){
			die('invalid public key');
		}
		$decrypted='';//decode must be done before spliting for getting the binary String
		$data=str_split(base64_decode($source),256);
		foreach($data as $chunk){
			$partial = '';//be sure to match padding
			$decryptionOK = openssl_public_decrypt($chunk,$partial,$public_key,OPENSSL_PKCS1_PADDING);
			if($decryptionOK===false){die('fail');}
				$decrypted.=$partial;
			}
		return $decrypted;
	}
?>