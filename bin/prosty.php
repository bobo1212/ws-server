<?php
//https://openswoole.com/how-it-works
//https://openswoole.com/docs/modules/swoole-timer
chdir(dirname(__DIR__));

require_once 'src/functions.php';

$topics = [
    'indicator-w8' => 9032,
    'signals' => 9033,
    'testowy' => 9034,
    'testowy2' => 9035
];

if (empty($argv[1])) {
    logMsg(LogLevel::INFO, 'Podaj nazwę topica:');
    foreach ($topics as $name => $port) {
        logMsg(LogLevel::INFO, "nazwa: " . $name . ' port:' . $port);
    }
    exit();
}

if (!array_key_exists($argv[1], $topics)) {
    logMsg(LogLevel::INFO, 'Niepoprawna nazwa topica:');
    foreach ($topics as $name => $port) {
        logMsg(LogLevel::INFO, "nazwa: " . $name . ' port:' . $port);
    }
    exit();
}

$server = new OpenSwoole\Websocket\Server("0.0.0.0", $topics[$argv[1]]);
$server->set(['worker_num' => 1]);


$producerList = new OpenSwoole\Table(1024 * 1000);
//$producerList->column('fd', OpenSwoole\Table::TYPE_INT, 4);
$producerList->create();

$server->on('open', function ($server, $req) use ($producerList) {

    if (basename($req->server['request_uri']) == 'producer') {
        logMsg(LogLevel::INFO, 'Dodanie producenta');
        $producerList->set($req->fd, []);
    }

    $count = count($server->connections);

    logMsg(LogLevel::INFO, "connection open: {$req->fd}" . ' total ' . $count);
});

$server->on('message', function ($server, $frame) use ($producerList) {
    //var_dump(get_class($server),get_class_methods($server));
    foreach ($server->connections as $fd) {

        if ($producerList->exists($fd)) {
            // nie wysyłamy do producentów
            continue;
        }

        if ($fd == $frame->fd) {
            logMsg(LogLevel::INFO, '====================== UNREGISTERED PRODUCER ======================================================');
            $cInfo = $server->getClientInfo($fd, -1, true);
            foreach ($cInfo as $k => $v) {
                if ($k == 'connect_time' || $k == 'last_time' || $k == 'last_recv_time' || $k == 'last_send_time' || $k == 'last_dispatch_time') {
                    $v = timastampToDate($v);
                }
                $k = str_pad($k, 30, " ", STR_PAD_LEFT);
                logMsg(LogLevel::INFO, $k . ': ' . $v);
            }
            logMsg(LogLevel::INFO, '=================== END UNREGISTERED PRODUCER ====================================================');
            continue;
        }


        $cInfo = $server->getClientInfo($fd, -1, true);
        if ($cInfo['send_queued_bytes'] != 0) {
            logMsg(LogLevel::INFO, '====================== SEND QUEUED  ======================================================');
            foreach ($cInfo as $k => $v) {
                if ($k == 'connect_time' || $k == 'last_time' || $k == 'last_recv_time' || $k == 'last_send_time' || $k == 'last_dispatch_time') {
                    $v = timastampToDate($v);
                }
                $k = str_pad($k, 40, " ", STR_PAD_LEFT);
                logMsg(LogLevel::INFO, $k . ': ' . $v);
            }
            logMsg(LogLevel::INFO, '=================== END SEND QUEUED ====================================================');
            continue;
        }
        $server->push($fd, $frame->data);
    }
});

$server->on('close', function ($server, $fd) use ($producerList) {

    if ($producerList->del($fd)) {
        logMsg(LogLevel::INFO, 'Usunięcie producenta');
    }
    $count = count($server->connections);
    logMsg(LogLevel::INFO, "connection close: {$fd}" . ' total ' . $count);
});
logMsg(LogLevel::INFO, "Server start");
$server->start();
