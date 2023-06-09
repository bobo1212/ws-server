<?php

namespace Bobo121278\WsServerOpenSwoole\app;

use Bobo121278\WsServerOpenSwoole\AppInterface;
use OpenSwoole\WebSocket\Server;
use OpenSwoole\WebSocket\Frame;
use OpenSwoole\Http\Request;

class AppAdmin implements AppInterface
{
    function onMessage(Server $server, Frame $frame, array $usersList)
    {
        $msgData = $this->decodeMsg($frame->data);
        $msg = $msgData['msg'];
        if ($msg == 'reload') {
            $server->push($frame->fd, 'ok');
            $server->reload();
            return;
        }
        if ($msg == 'server') {
            $server->push($frame->fd, json_encode([
                "msg" => "server",
                'stats' => $server->stats()
            ]));
            return;
        }
        if ($msg == 'client') {
            $lientsInfo = [];
            foreach ($server->getClientList(0, 10) as $fd) {
                $clientInfo = $server->getClientInfo($fd);
                $clientInfo['uri'] = getUri($fd);
                $lientsInfo[] = $clientInfo;
            }
            $server->push($frame->fd, json_encode([
                "msg" => "client",
                "client" => $lientsInfo,
            ]));
            return;
        }
        if ($msg == 'app') {
            global $appConfig;
            $applist = [];
            foreach ($appConfig as $uri => $app) {
                $applist[] = [
                    'uri' => $uri,
                    'name'=> $app->getAppName(),
                    'client' => count(getUsers($uri))
                ];
            }
            $server->push($frame->fd, json_encode([
                "msg" => "app",
                "app" => $applist,
            ]));
            return;
        }
        $server->push($frame->fd, 'Jetseś adminem :-)');
    }

    public function onClose(Server $server, int $fd)
    {

    }

    public function onOpen(Server $server, Request $request): string
    {
        // TODO: Implement onOpen() method.
        return '';
    }

    private function decodeMsg(string $msg): array
    {
        $msg = @json_decode($msg, true);
        if (is_array($msg)) {
            return $msg;
        }
        return [];
    }

    public function getAppName(): string
    {
        return 'Admin';
    }
}