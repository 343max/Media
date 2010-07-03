<?php

function __autoload($class_name) {
    require_once dirname(__FILE__) . '/classes/class.' . $class_name . '.php';
}

define('mediadir', dirname(__FILE__) . '/media/');
define('mediahost', 'http://localhost/');
define('mediaurl', mediahost . 'media/');
define('sitename', 'Media');

ini_set('include_path', ini_get('inlcude_path') . ':' . dirname(__FILE__));

define('prowlUser', 'your prowl username');
define('prowlPassword', 'your prowl password');
