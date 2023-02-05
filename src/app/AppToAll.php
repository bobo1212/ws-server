<?php

namespace Bobo121278\WsServerOpenSwoole\app;

use Bobo121278\WsServerOpenSwoole\AppInterface;
use OpenSwoole\WebSocket\Server;
use OpenSwoole\WebSocket\Frame;

class AppToAll implements AppInterface
{
    function onMessage(Server $server, Frame $frame, array $usersList)
    {
        foreach ($usersList as $fd => $user) {
            if ($fd == $frame->fd) {
                continue;
            }
            $server->push($fd, $frame->data);
        }
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