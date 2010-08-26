<?php

require_once('../../config.inc.php');

$fileUrl = $_GET['url'];
$fileName = $_GET['fileName'];

if(!$fileUrl) {
	die(json_encode(array('result' => 'no url given')));
}

$pool = new BackgroundPool(serviceurl);

$handler = new MediaHandler($fileUrl, $fileName, putio_apikey, putio_password);


//$handler->runThread();
$pool->addAndRun($handler);

echo json_encode(array('result' => 'ok'));