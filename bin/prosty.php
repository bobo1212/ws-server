<?php

$topics = [
    'indicator-w8' => 9032,
    'signals' => 9033,
    'testowy' => 9034
];

if (empty($argv[1])) {
    echo 'Podaj nazwętopica:' . "\n";
    foreach ($topics as $name => $port) {
        echo "nazwa: " . $name .' port:'. $port ."\n";
    }
    exit();
}

if(!array_key_exists($argv[1], $topics)){
    echo 'Niepoprawna nazwa topica:' . "\n";
    foreach ($topics as $name => $port) {
        echo "nazwa: " . $name .' port:'. $port ."\n";
    }
    exit();
}

$server = new OpenSwoole\Websocket\Server("0.0.0.0", $topics[$argv[1]]);
$server->set(['worker_num' =>1]);

$server->on('open', function ($server, $req) {

    echo "connection open: {$req->fd}\n";
});

$server->on('message', function ($server, $frame) {
    foreach ($server->connections as $fd) {
        if ($fd == $frame->fd) {
            continue;
        }
        $server->push($fd, $frame->data);
    }
});

$server->on('close', function ($server, $fd) {
    echo "connection close: {$fd}\n";
});

$server->start();
