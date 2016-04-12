<?php
/**
 * test header for autoload
 */
require_once __DIR__ . '/../../vendor/autoload.php';
putenv('AppEnv=unit_test');
// init app but don't run so we can test
\Flickrer\App::singleton();
