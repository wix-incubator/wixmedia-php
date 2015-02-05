<?php

class WixAuthToken {
	private $api_key;
	private $secret;
	private $auth_url;
	private $token;
	private $auth_scheme;

	public function __construct($api_key, $secret, $auth_url) {
		if (!$api_key || !$secret || !$auth_url) throw new Exception("Not ready to authenticate", 30);
		
		$this->api_key = $api_key;
		$this->secret = $secret;
		$this->auth_url = $auth_url;
	}

	// interface
	public function getToken() {
		if (!$this->token) $this->token = $this->createToken();
		return $this->token;
	}

	public function getAuthorizationHeader() {
		$this->getToken();
		return array('Authorization' => $this->auth_scheme . ' ' . $this->token);
	}

	public function createToken() {
		$mct = explode(" ",microtime());
		$headers = array(
			'x-wix-auth-nonce' => bin2hex(openssl_random_pseudo_bytes(6)),
			'x-wix-auth-ts' => date("Y-m-d\TH:i:s",$mct[1]).substr((string)$mct[0],1,4).'Z'
		);
		ksort($headers);
		$canonicalized = $this->canonicalizeParams($headers);
		$signed_hmac = $this->signString($canonicalized, $this->secret);
		$signed_web_safe = $this->base64url_encode($signed_hmac);
		$auth_header = sprintf("%s %s:%s", 'WIX', $this->api_key, $signed_web_safe);
		$headers['Authorization'] = $auth_header;
		$answer = WixHttpUtils::curl($this->auth_url, $headers);
		if ($answer['http_code'] === 200) {
			$response = json_decode($answer['result']);
			$this->auth_scheme = $response->scheme;
			$this->token = $response->token;
			return $this->token;
		}
		// echo '<pre>'.print_r($canonicalized, true).'</pre>';
		// echo '<pre>'.print_r($auth_header, true).'</pre>';
		// echo '<pre>'.print_r($answer, true).'</pre>';
	}

	// private part
	private function base64url_encode($data) {
	  return strtr(base64_encode($data), '+/', '-_');
	}

	private function canonicalizeParams($add_params = array()) {
		$new_add_param = array();
		foreach ($add_params as $key => $value) {
			$new_add_param[] = $key.':'.$value;
		}
		$params = array_merge(array(
			'GET',
			parse_url($this->auth_url, PHP_URL_PATH)
		), $new_add_param);
		return implode("\n", $params);
	}

	private function signString($string, $secret) {
		return hash_hmac('sha256', $string, $secret, true);
	}
}