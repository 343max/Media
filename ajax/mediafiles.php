<?php

require_once('../config.inc.php');

$d = dir(mediadir);

$files = array();

while (false !== ($entry = $d->read())) {
	if(preg_match("/^\\./", $entry)) continue;
	$file = (object)null;

	$file->name = $entry;
	$file->url = mediaurl . $entry;
	$file->modificationTime = filemtime(mediadir . $entry);
	$file->creationTime = filectime(mediadir . $entry);

	$files[] = $file;
}

$object = (object)null;
$object->mediafiles = $files;

echo json_encode($object);

?>