<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 02.02.2019 22:03
 */

if (!defined('APPLICATION_ROOT')) {
    define('APPLICATION_ROOT', realpath(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
}
require_once(APPLICATION_ROOT . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

if (!defined('CONFIGURATION_ROOT')) {
    define('CONFIGURATION_ROOT', APPLICATION_ROOT . 'configuration' . DIRECTORY_SEPARATOR);
}
if (!defined('DATA_SOURCE')) {
    define('DATA_SOURCE', CONFIGURATION_ROOT . 'test-datasource.php');
}
