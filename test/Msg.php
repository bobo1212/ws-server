<?php

use WebSocket\Client;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './vendor/autoload.php';

$config = require_once('./test/config.php');
$client = new Client($config['url']);
$lp = 0;
while (true) {
    $client->send('{"type":"msg","msg":"testMsg ' . (++$lp) . '","topic":"myTestTopic"}');
    echo $lp. "\n";
}



