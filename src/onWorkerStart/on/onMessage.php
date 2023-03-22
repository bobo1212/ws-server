<?php


use OpenSwoole\WebSocket\Server;
use OpenSwoole\WebSocket\Frame;

function onMessage(Server $server, Frame $frame)
{
    global $appConfig;
    $requestUri = getUri($frame->fd);
    $usersList = getUsers($requestUri);
    /* TODO nie przekazywać parametru $usersList */
    $appConfig[$requestUri]->onMessage($server, $frame, $usersList);
}