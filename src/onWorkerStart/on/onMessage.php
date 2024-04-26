<?php


use OpenSwoole\WebSocket\Server;
use OpenSwoole\WebSocket\Frame;

function onMessage(Server $server, Frame $frame)
{
    global $appConfig;
    $requestUri = getUri($frame->fd);
    $appConfig[$requestUri]->onMessage($server, $frame);
}