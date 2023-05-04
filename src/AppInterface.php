<?php

namespace Bobo1212\WsServerOpenSwoole;

use OpenSwoole\WebSocket\Server;
use OpenSwoole\Http\Request;
use OpenSwoole\WebSocket\Frame;

interface AppInterface
{

    public function getAppName(): string;
    public function onOpen(Server $server, Request $request);
    public function onMessage(Server $server, Frame $frame, array $users);
    public function onClose(Server $server, int $fd);
    /*
    OpenSwoole\WebSocket\Server->on('Start', fn)
    OpenSwoole\WebSocket\Server->on('Handshake, fn)
    OpenSwoole\WebSocket\Server->on('Open, fn)
    OpenSwoole\WebSocket\Server->on('Message, fn)
    OpenSwoole\WebSocket\Server->on('Request, fn)
    OpenSwoole\WebSocket\Server->on('Close, fn)
    */
}