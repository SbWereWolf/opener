<?php
/**
 * Project: opener
 * Author: SbWereWolf
 * DateTime: 01.01.2019 0:08
 */

try {
    (require('configuration.php'))->run();
} catch (Exception $e) {
    echo $e->getMessage();
}
