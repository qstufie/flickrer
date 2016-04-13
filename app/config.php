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
    'salt' => '<userpass_salt>',
    'items_per_page' => 5,
    'max_recent_searches' => 5
];

// dev
$dev = $prod;

$dev['displayErrorDetails'] = true;

// hey this is my test key, don't abuse it thanks
$dev['endpoint'] = 'https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=99ea7408ecb4fe4e18b37131fa5a9455&api_secret=528d383dd2cad018&format=json&nojsoncallback=1';
$dev['db']['host']   = "127.0.0.1";
$dev['db']['user']   = "dev";
$dev['db']['pass']   = "pass123";
$dev['salt'] = 'dev-salt';

// vagrant box
$vagrant = $dev;
$vagrant['db']['host']   = "localhost";
$dev['db']['user']   = "root";
$dev['db']['pass']   = "root";
$vagrant['salt'] = 'vagrant-salt';

// unit_test
$unit_test = $dev;
$unit_test['db']['dbname'] = 'flickrer_test';
$unit_test['salt'] = 'unit-test-salt';

return [
    'dev' => $dev,
    'vagrant' => $vagrant,    
    'unit_test' => $unit_test,
    'prod' => $prod
];
