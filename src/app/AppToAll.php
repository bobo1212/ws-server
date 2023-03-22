<?php

namespace Bobo121278\WsServerOpenSwoole\app;

use Bobo121278\WsServerOpenSwoole\AppInterface;
use OpenSwoole\Constant;
use OpenSwoole\WebSocket\Server;
use OpenSwoole\WebSocket\Frame;
use OpenSwoole\Http\Request;

class AppToAll implements AppInterface
{
    function onMessage(Server $server, Frame $frame, array $usersList)
    {
        foreach ($usersList as $fd => $user) {
            if ($fd == $frame->fd) {
                continue;
            }
            $ret = $server->push($fd, $frame->data);
            if ($ret === false) {
                $server->close($fd);
                removeFromTable($fd);
            }
        }
    }

    public function onClose(Server $server, int $fd)
    {

    }

    public function onOpen(Server $server, Request $request): string
    {
        // TODO: Implement onOpen() method.
        return '';
    }

    public function getAppName(): string
    {
        return 'To all';
    }
}