<?php

require_once('../config.inc.php');

$fileUrl = $_GET['url'];

if(!$fileUrl) {
	die(json_encode(array('result' => 'no url given')));
}

$pool = new BackgroundPool(serviceurl);

$handler = new MediaHandler($fileUrl, filehost_username, filehost_password);


//$handler->runThread();
$pool->addAndRun($handler);

echo json_encode(array('result' => 'ok'));