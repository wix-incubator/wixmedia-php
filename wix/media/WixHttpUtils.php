<?php

class WixHttpUtils {

	static public function curl($url, $headers = array(), $params = array(), $method = 'GET') {
		$ch = curl_init(); //инициализация сеанса
		curl_setopt($ch, CURLOPT_URL, $url); //урл сайта к которому обращаемся
		switch ($method) {
			case 'DELETE':
			case 'OPTION':
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST , $method);
				break;
			case 'POST':
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
				break;
		}
		if (is_array($headers) && count($headers)) {
			foreach ($headers as $key => $value) {
				$curl_headers[] = $key.':'.$value;
			}
			curl_setopt($ch, CURLOPT_HTTPHEADER, $curl_headers);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // вернуть результат
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, 156 , 6000); // CURLOPT_CONNECTTIMEOUT_MS  до 6 секунд пытаемся соединиться с сервером
		curl_setopt($ch, 155 , 15000); // CURLOPT_TIMEOUT_MS до 15 секунд ждем ответа сервера
		$res = curl_exec($ch);
		$ch_error = curl_error($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if ($http_code !== 200 && $http_code !== 404) error_log("WixHttpUtils::curl, http_code: $http_code, curl_error: $ch_error");
		return array('http_code' => $http_code, 'error_message' => $ch_error, 'result' => $res);
	}
}

if (!function_exists('curl_file_create')) {
    function curl_file_create($filename, $mimetype = '', $postname = '') {
        return "@$filename;filename="
            . ($postname ?: basename($filename))
            . ($mimetype ? ";type=$mimetype" : '');
    }
}