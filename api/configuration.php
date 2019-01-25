<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 02.01.2019 16:12
 */

use Environment\Setup\Setup;
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

$app = null;
try {
    $app = (new Setup(new \Slim\App($container)))
        ->perform();
} catch (Exception $e) {
    echo $e->getMessage();
}
return $app;
