<?php


use WebSocket\Client;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once './vendor/autoload.php';

$topicName = 'myTestTopic';

$client = new Client("ws://localhost:9032/topics");
$client->send('{"type":"subscribe","topic":"' . $topicName . '"}');
while (true) {
    try {
        echo $client->receive() . "\n";
    } catch (WebSocket\TimeoutException $e) {
        echo 'TimeoutException: ' . $e->getMessage() . "\n";
    }
}