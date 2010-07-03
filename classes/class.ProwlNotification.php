<?php

require_once('HTTP/Request.php');

class ProwlNotification {
	var $username = '';
	var $password = '';

	function __construct($username, $password) {
		$this->username = $username;
		$this->password = $password;
	}

	function send($application, $event, $description) {
		//https://prowl.weks.net/api/add_notification.php?application=%s&event=%s&description=%s

		$request = new HTTP_Request('https://prowl.weks.net/api/add_notification.php?application=' . urlencode($application) . '&event=' . urlencode($event) . '&description=' . urlencode($description));
		$request->setBasicAuth($this->username, $this->password);

		$response = $request->sendRequest();
	}

}

