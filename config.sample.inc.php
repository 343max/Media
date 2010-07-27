<?php

ini_set('include_path', ini_get('include_path') . ':' . dirname(__FILE__) . '/classes/:' . dirname(__FILE__) . '/external/backgrounder/classes/');

function __autoload($class_name) {
    require_once 'class.' . $class_name . '.php';
}

function __autoload($class_name) {
    require_once 'class.' . $class_name . '.php';
}

define('mediadir', dirname(__FILE__) . '/media/');
define('mediahost', 'http://localhost/');
define('mediaurl', mediahost . 'media/');
define('sitename', 'Media');

define('cachepath', dirname(__FILE__) . '/cache/');

define('prowlUser', 'your prowl username');
define('prowlPassword', 'your prowl password');

define('filehost_filelist', 'http://yourfileserverurl/files.json.php');
define('filehost_username', 'username');
define('filehost_password', base64_decode('password'));