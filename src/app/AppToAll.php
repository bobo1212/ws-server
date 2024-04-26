<?php

namespace Bobo1212\WsServerOpenSwoole\app;

use Bobo1212\WsServerOpenSwoole\AppInterface;
use OpenSwoole\Constant;
use OpenSwoole\WebSocket\Server;
use OpenSwoole\WebSocket\Frame;
use OpenSwoole\Http\Request;

class AppToAll implements AppInterface
{
    function onMessage(Server $server, Frame $frame)
    {
        $requestUri = getUri($frame->fd);
        $users = getUsers($requestUri);
        foreach ($users as $fd => $user) {
            if ($fd == $frame->fd) {
                continue;
            }
            $ret = $server->push($fd, $frame->data);
            if ($ret === false) {
                $server->close($fd,true);
                removeFromTable($fd);
            }
        }
    }

    public function onClose(Server $server, int $fd)
    {

    }

    public function onOpen(Server $server, Request $request)
    {
        // TODO: Implement onOpen() method.
    }

    public function getAppName(): string
    {
        return 'To all';
    }
}