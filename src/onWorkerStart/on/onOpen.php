<?php
use OpenSwoole\WebSocket\Server;
use OpenSwoole\Http\Request;

function onOpen(Server $server, Request $request){
    setUser($request->server['request_uri'], $request->fd);
    setUri($request->fd, $request->server['request_uri']);
    $users = getUsers($request->server['request_uri']);
    logMsg(LogLevel::INFO, 'connection open: ' . $request->fd . ' ' . $request->server['request_uri'] . ' ' . count($users));
}