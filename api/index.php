<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 0:08
 */

use Environment\Routing;
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

$app = new \Slim\App($container);

$isSuccess = (new Routing($app))->initialize();

if ($isSuccess) {
// Run app
    try {
        $app->run();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
