<?php

use WebSocket\Client;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './vendor/autoload.php';

$config = require_once('./test/config.php');
$client = new Client($config['url']);
$lp = 0;
//10240
$balast  = str_repeat('.', 10240 * 66);

while (true) {
    $lp++;
    $payload = [
        'producerId' => $argv[1],
        'messageId' => $lp,
       // 'balast' => $balast
    ];

    $msg = [
        'type' => 'msg',
        'msg' => $payload,
        'topic' => 'myTestTopic',
    ];

    $client->send(json_encode($msg));
    //echo $lp. "\n";
usleep((int)(1000000/1));
}



