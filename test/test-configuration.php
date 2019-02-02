<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 02.02.2019 22:03
 */

if (!defined('APPLICATION_ROOT')) {
    define('APPLICATION_ROOT', realpath(__DIR__) . DIRECTORY_SEPARATOR . '..');
}
require_once(APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

if (!defined('CONFIGURATION_ROOT')) {
    define('CONFIGURATION_ROOT', APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'configuration');
}
if (!defined('DATA_PATH')) {
    define('DATA_PATH', CONFIGURATION_ROOT . DIRECTORY_SEPARATOR . 'test-latch.sqlite');
}
