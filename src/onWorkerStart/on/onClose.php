<?php

use OpenSwoole\WebSocket\Server;
use OpenSwoole\WebSocket\Frame;

function onClose(Server $server, int $fd){


    global $appConfig;
    $requestUri = getUri($fd);
    $usersList = getUsers($requestUri);
    $appConfig[$requestUri]->onClose($server, $fd);

    $requetUri = getUri($fd);
    removeFromTable($fd);
    $users = getUsers($requetUri);
    logMsg(LogLevel::INFO, 'connection close: ' . $fd . ' ' . $requetUri . ' ' . count($users));
}