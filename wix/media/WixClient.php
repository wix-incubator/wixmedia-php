<?php 

require('WixAuthToken.php');
require('WixHttpUtils.php');
require('WixMedia.php');
require('WixImage.php');

class WixClient {
	const IMAGE_SERVICE_HOST         			= 'media.wixapps.net';
	const VIDEO_SERVICE_HOST         			= 'storage.googleapis.com';
	const METADATA_SERVICE_HOST      			= 'mediacloud.wix.com';
	const WIX_MEDIA_IMAGE_UPLOAD_URL 			= 'http://mediacloud.wix.com/files/upload/url';
	const WIX_MEDIA_AUDIO_UPLOAD_URL 			= 'http://mediacloud.wix.com/files/upload/url';
	const WIX_MEDIA_VIDEO_UPLOAD_URL 			= 'http://mediacloud.wix.com/files/video/upload/url';
	const WIX_MEDIA_AUTH_TOKEN_URL   			= 'http://mediacloud.wix.com/auth/token';
	const WIX_MEDIA_GET_FILE_INFO_URL_PREFIX 	= 'http://mediacloud.wix.com/files/';

	private $auth;

	public function __construct($api_key, $secret) {
		$this->auth = new WixAuthToken($api_key, $secret, self::WIX_MEDIA_AUTH_TOKEN_URL);
	}

	public function getAuth() {
		return $this->auth;
	}

	public function getImageById($image_id) {
		return new WixImage($image_id, self::IMAGE_SERVICE_HOST, $this);
	}

	public function getMetadata($metadata_id) {
		$url = self::WIX_MEDIA_GET_FILE_INFO_URL_PREFIX . $metadata_id;
		$headers = $this->getAuth()->getAuthorizationHeader();
		$answer = WixHttpUtils::curl($url, $headers);
		if ($answer['http_code'] === 200) return json_decode($answer['result']);
		else return false;
	}

	private function getUploadUrl($url_endpoint) {
		$headers = $this->getAuth()->getAuthorizationHeader();
		$answer = WixHttpUtils::curl($url_endpoint, $headers);

		if ($answer['http_code'] === 200) return json_decode($answer['result'])->upload_url;
		else return false;
	}

	public function uploadImage($file_path) {
		if (!file_exists($file_path) || !is_file($file_path)) return false;
		
		$image_info = getimagesize($file_path);
		return $this->uploadFile($file_path, self::WIX_MEDIA_IMAGE_UPLOAD_URL, 'picture', $image_info['mime']);
	}

	private function uploadFile($file_path, $url_endpoint, $media_type, $mime) {
		$url = $this->getUploadUrl($url_endpoint);
		$headers = $this->getAuth()->getAuthorizationHeader();
		$file_path = realpath($file_path);
		$filename = pathinfo($file_path, PATHINFO_BASENAME);
		$post_fields['media_type'] = $media_type;
		$post_fields['file'] = curl_file_create($filename, $mime, $filename);
		$answer = WixHttpUtils::curl($url, $headers, $post_fields, 'POST');

		if ($answer['http_code'] === 200) return json_decode($answer['result']);
		else return false;
	}

}