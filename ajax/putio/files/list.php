<?php

require_once('../../../config.inc.php');

$parentId = (int)$_GET['parent_id'];

$putIo = new PutIo();

echo $putIo->callFilesList($parentId);