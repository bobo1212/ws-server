<?php


use WebSocket\Client;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once './vendor/autoload.php';

$topicName = 'myTestTopic';

$config = require_once('./test/config.php');
$client = new Client($config['url']);
$client->send('{"type":"subscribe","topic":"' . $topicName . '"}');


$messageId = [];
$lp=0;
while (true) {

    try {
        $receive = $client->receive();
        $msg = json_decode($receive, true);
        var_dump($msg['msg']['messageId']);
        continue;
        if (!array_key_exists($msg['producerId'], $messageId)) {
            $messageId[$msg['producerId']] = $msg['messageId'];
            continue;
        }
        $messageId[$msg['producerId']]++;
        if ($msg['messageId'] != $messageId[$msg['producerId']]) {
            echo $msg['messageId'] . ' - ' . $messageId[$msg['producerId']] . ' = ' . ($msg['messageId'] - $messageId[$msg['producerId']]) . "\n";
            $messageId[$msg['producerId']] = $msg['messageId'];
        }
    } catch (WebSocket\TimeoutException $e) {
        echo 'TimeoutException: ' . $e->getMessage() . "\n";
    }
}