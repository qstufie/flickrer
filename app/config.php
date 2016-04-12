<?php
/**
 * settings
 * NOTE: this is an example but in real world if you use, say bamboo,
 * to build the pipeline, you'll want to keep the u/p in bamboo settings (or better yet, generate during building of RDS)
 * then replace the placeholders, thus the placeholders for PROD
 */
// prod
$prod = [
    'displayErrorDetails' => false,
    'db' => [
        'host' => '<db_host>',
        'user' => '<db_user>',
        'pass' => '<db_pass>',
        'dbname' => 'flickrer'
    ],
    'endpoint' => '<flickr_ep>',
    'salt' => '<userpass_salt>'
];

// dev
$dev = $prod;

$dev['displayErrorDetails'] = true;
// just use the default flickr key for this one, hopefully they won't find out...
$dev['endpoint'] = 'https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=a1deede842a6d0bd9d6c33f92bf7165d&format=json';
$dev['db']['host']   = "127.0.0.1";
$dev['db']['user']   = "dev";
$dev['db']['pass']   = "pass123";
$dev['salt'] = 'dev-salt';

// unit_test
$unit_test = $dev;
$unit_test['db']['dbname'] = 'flickrer_test';
$unit_test['salt'] = 'unit-test-salt';

return [
    'dev' => $dev,
    'unit_test' => $unit_test,
    'prod' => $prod
];
