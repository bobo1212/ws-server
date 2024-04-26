<?php



use WebSocket\Client;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once './vendor/autoload.php';

$topicName = 'myTopicxxx';
$app= 'topics';

//$client = new Client("ws://192.168.0.13:9032/toall");
//$client = new Client("ws://20.203.223.233/toall");
//$client = new Client("ws://bot.test/toall");
$client = new Client("ws://localhost:9032/".$app,['headers' =>[
    'Authorization' => 'Bearer xxxxx'
]]);
$client->send('{"type":"create_topic","topic":"'.$topicName.'"}');
$lp =0;

while (true) {
    $client->send('{"type":"msg","topic":"'.$topicName.'","msg":"'.$topicName.' '.(++$lp).'"}');
    echo 'msg ' . $lp ."\n";
    usleep(100000);
}