<?php

require_once('../config.inc.php');

$d = dir(tmpdir);

$processes = array();

while (false !== ($entry = $d->read())) {
	if(!preg_match("/\\.json\$/", $entry)) continue;

	$processes[] = file_get_contents(tmpdir . $entry);
}

echo '[' . join(', ', $processes) . ']';
