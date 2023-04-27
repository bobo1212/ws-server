<?php


use WebSocket\Client;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './vendor/autoload.php';

$topicName = 'myTopicxxx';
$app= 'topics';

$client = new Client("ws://localhost:9032/".$app, ['timeout' => 60*5]);
$client->send('{"type":"subscribe","topic":"'.$topicName.'"}');

echo 'Start consume'."\n";
while (true) {
    $msg = $client->receive();
    var_dump($msg);
}

