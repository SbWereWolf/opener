<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 02.01.2019 16:12
 */

use Environment\Setup\Setup;
use Slim\Container;

if (!defined('APPLICATION_ROOT')) {
    define('APPLICATION_ROOT', realpath(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
}
require_once(APPLICATION_ROOT . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

if (!defined('CONFIGURATION_ROOT')) {
    define('CONFIGURATION_ROOT', APPLICATION_ROOT . 'configuration' . DIRECTORY_SEPARATOR);
}
if (!defined('DATA_PATH')) {
    define('DATA_PATH', CONFIGURATION_ROOT . 'latch.sqlite');
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
