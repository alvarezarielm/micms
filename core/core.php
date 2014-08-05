<?php 

// Loader
require 'app/bootstrap.php';

require 'core/database.php';
require 'core/session.php';
require 'core/controller.php';
require 'core/view.php';
require 'core/model.php';
require 'core/collection.php';
require 'core/helper/helper.php';

$app = new Bootstrap();
$app->init();