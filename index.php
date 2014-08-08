<?php
define('BASE_PATH', dirname(__FILE__));
define('BASE_URL', 'http://'.$_SERVER['HTTP_HOST'].'/');
//Display errors
ini_set('display_errors', 1);

//Iniciamos el core
require_once 'core/core.php';
