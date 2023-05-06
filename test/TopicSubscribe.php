<?php



use WebSocket\Client;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once './vendor/autoload.php';

$topicName = 'myTestTopic';

$config = require_once('./test/config.php');
$client = new Client($config['url']);
$client->send('{"type":"subscribe","topic":"'.$topicName.'"}');
sleep(10);