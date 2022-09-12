<?php

error_reporting(E_ALL);
//error_reporting(E_ALL & ~(E_NOTICE | E_WARNING | E_DEPRECATED | E_STRICT));
//
// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(__DIR__ . '/../application'));

// Define application environment
//defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
//
// load composer autoload
require_once realpath(__DIR__ . '/../vendor/autoload.php');

$confPath = APPLICATION_PATH . '/../config/application.ini';

// Create application, bootstrap, and run
$application = new \Core\Application($confPath);
$application->bootstrap();
$application->run();
