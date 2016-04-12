<?php
/**
 * Flickrer main
 *
 */
// change time to your local, for now Syd is used.
date_default_timezone_set('Australia/Sydney');

// bootstrap
require '../vendor/autoload.php';
\Flickrer\App::singleton()->run();
