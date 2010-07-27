<?php

require_once('../config.inc.php');

$server = new BackgroundServer();
$server->serve();