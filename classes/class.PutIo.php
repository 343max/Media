<?php


class PutIo {
	const apiHost = 'api.put.io';
	const apiUrl = '/v1/';
	
	private $apiKey = null;
	private $apiSecret = null;

	public function __construct() {
		$this->apiKey = putio_apikey;
		$this->apiSecret = putio_apisecret;
	}

	public static function getEmptyObject() {
		return (object)null;
	}

	private function getRequestObject($parameters = null) {
		if(!is_object($parameters))
			$parameters = self::getEmptyObject();
		
		$request = self::getEmptyObject();
		$request->api_key = $this->apiKey;
		$request->api_secret = $this->apiSecret;
		$request->params = $parameters;

		return $request;
	}

	private function postRequest($host, $url, $postData) {
		$request = array(
			"POST " . $url . " HTTP/1.1",
			"Host: $host",
			"Accept: application/json",
			"Content-Length: " . strlen($postData),
			"Content-Type: application/x-www-form-urlencoded",
			"Connection: close",
			"",
			$postData
		);

		$socketPointer = fsockopen($host, 80);

		if(!$socketPointer) return '';

		fwrite($socketPointer, join("\r\n", $request));

		$httpResponse = '';

		while (!feof($socketPointer)) {
			$httpResponse .= fread($socketPointer, 8192);
		}

		fclose($socketPointer);

		return substr($httpResponse, stripos($httpResponse, "\r\n\r\n") + 4);
	}

	private function getRequestString($parameters = null) {
		return json_encode($this->getRequestObject($parameters));
	}

	public function postCall($className, $methodName, $parameters = null) {
		return $this->postRequest(self::apiHost, self::apiUrl . $className . '?method=' . $methodName, 'request=' . $this->getRequestString());
	}

	public function callFilesList() {
		return $this->postCall('files', 'list');
	}
}