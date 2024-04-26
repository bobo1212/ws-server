<?php


use WebSocket\Client;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './vendor/autoload.php';


$client = new Client("ws://192.168.0.13:9032/", ['timeout' => 60*5]);


echo 'Start consume'."\n";
while (true) {
    $msg = $client->receive();
    echo $msg ."\n";
}

