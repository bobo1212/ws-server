<?php

namespace Bobo1212\WsServerOpenSwoole\app;

use Bobo1212\WsServerOpenSwoole\AppInterface;
use OpenSwoole\WebSocket\Server;
use OpenSwoole\WebSocket\Frame;
use OpenSwoole\Http\Request;

class AppClientServer implements AppInterface
{
    /**
     * @var string
     */
    private string $uniqueName;

    public function __construct(string $uniqueName)
    {
        $this->uniqueName = $uniqueName;
    }

    function onMessage(Server $server, Frame $frame, array $users)
    {
        $msg = $this->decodeMsg($frame->data);
        if (empty($msg)) {
            return;
        }
        if (array_key_exists('server', $msg)) {
            $this->setServer($frame->fd);
            return;
        }
        $serverFd = $this->getServer();
        if ($serverFd == $frame->fd) {
            $this->serverMsg($server, $users, $msg);
        } else {
            $this->clientMsg($server, $frame, $msg);
        }
    }

    private function setServer($fd)
    {
        global $memory;
        $memory->set($this->uniqueName, $fd);
    }

    private function getServer(): int
    {
        global $memory;
        return (int)$memory->get($this->uniqueName);
    }

    private function removeServer()
    {
        global $memory;
        $memory->del($this->uniqueName);
    }

    private function clientMsg(Server $server, Frame $frame, array $msg)
    {
        $msgToServer = [
            'msg' => $msg['msg'],
            'to' => $frame->fd
        ];
        $server->push($this->getServer(), $this->encodeMsg($msgToServer));
    }

    private function serverMsg(Server $server, array $users, array $msg)
    {
        if (
            array_key_exists('msg', $msg) &&
            array_key_exists('to', $msg) &&
            array_key_exists($msg['to'], $users)
        ) {
            $server->push($msg['to'], $this->encodeMsg([
                'msg' => $msg['msg']
            ]));
        }
    }

    public function onClose(Server $server, int $fd)
    {
        $serverFd = $this->getServer();
        if ($serverFd == $fd) {
            $this->removeServer();
        }
    }

    private function decodeMsg(string $msg): array
    {
        $msg = @json_decode($msg, true);
        if (is_array($msg)) {
            return $msg;
        }
        return [];
    }

    /**
     * @param array $msg
     * @return string
     */
    private function encodeMsg(array $msg): string
    {
        $msg = @json_encode($msg);
        if (is_string($msg)) {
            return $msg;
        }
        return '';
    }

    public function onOpen(Server $server, Request $request)
    {
    }

    public function getAppName(): string
    {
        return 'Client Server';
    }
}