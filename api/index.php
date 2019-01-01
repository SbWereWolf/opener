<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 0:08
 */

use Slim\Container;

define('APPLICATION_ROOT', realpath(__DIR__) . DIRECTORY_SEPARATOR . '..');
require APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

define('CONFIGURATION_ROOT', APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'configuration');
if (!defined('DATA_PATH')) {
    define('DATA_PATH', CONFIGURATION_ROOT . DIRECTORY_SEPARATOR . 'latch.sqlite');
}

// Create and configure Slim app
$configuration['displayErrorDetails'] = true;
$configuration['addContentLengthHeader'] = false;
$container = new Container(['settings' => $configuration]);

try {
    (new \Environment\RouteSet(new \Slim\App($container)))
        ->setUp()
        ->run();
} catch (Exception $e) {
    echo $e->getMessage();
}
