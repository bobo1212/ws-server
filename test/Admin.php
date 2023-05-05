<?php



use WebSocket\Client;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once './vendor/autoload.php';

$client = new Client("ws://localhost:9032/topics");
$client->send('{"type":"admin"}');
echo $client->receive()."\n";


