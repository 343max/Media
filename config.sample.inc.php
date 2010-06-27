<?php

define('mediadir', dirname(__FILE__) . '/media/');
define('mediahost', 'http://localhost/');
define('mediaurl', mediahost . 'media/');
define('sitename', 'Media');

ini_set('include_path', ini_get('inlcude_path') . ':' . dirname(__FILE__));