<?php

use OpenSwoole\WebSocket\Server;
use OpenSwoole\WebSocket\Frame;

function onClose(Server $server, int $fd)
{
    global $appConfig;
    $requestUri = getUri($fd);
    $appConfig[$requestUri]->onClose($server, $fd);
    removeFromTable($fd);
    $users = getUsers($requestUri);
    logMsg(LogLevel::INFO, 'connection close: ' . $fd . ' ' . $requestUri . ' ' . count($users));
}