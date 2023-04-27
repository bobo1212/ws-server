<?php

use OpenSwoole\WebSocket\Server;
use OpenSwoole\Http\Request;

function onOpen(Server $server, Request $request)
{
    global $appConfig;
    if (!array_key_exists($request->server['request_uri'], $appConfig)) {
        $server->disconnect($request->fd, Server::WEBSOCKET_CLOSE_NORMAL, '404');
        return;
    }
    setUser($request->server['request_uri'], $request->fd);
    setUri($request->fd, $request->server['request_uri']);

    $users = getUsers($request->server['request_uri']);
    logMsg(LogLevel::INFO, 'connection open: ' . $request->fd . ' ' . $request->server['request_uri'] . ' ' . count($users));

    $appConfig[$request->server['request_uri']]->onOpen($server, $request);
}