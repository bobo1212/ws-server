<?php

namespace Bobo121278\WsServerOpenSwoole\app;

use Bobo121278\WsServerOpenSwoole\AppInterface;
use OpenSwoole\WebSocket\Server;
use OpenSwoole\WebSocket\Frame;

class AppAdmin implements AppInterface
{
    function onMessage(Server $server, Frame $frame, array $usersList)
    {
        if ($frame->data == 'reload') {
            $server->reload();
            return;
        }
        $server->push($frame->fd, 'Jetseś adminem :-)');
    }

    public function onClose(Server $server, int $fd)
    {

    }

    public function onOpen(): string
    {
        // TODO: Implement onOpen() method.
        return '';
    }
}