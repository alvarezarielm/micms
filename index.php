<?php
$self = explode('/', $_SERVER['REQUEST_URI']);
define('BASE_PATH', dirname(__FILE__));
define('BASE_URL_FOLDER', (isset($self[1])) ? $self[1] : '');
define('BASE_URL', 'http://'.$_SERVER['HTTP_HOST'].'/'.BASE_URL_FOLDER.'/');
//Display errors
ini_set('display_errors', 1);

//Iniciamos el core
require_once 'core/core.php';
