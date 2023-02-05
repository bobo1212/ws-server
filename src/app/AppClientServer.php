<?php

namespace Bobo121278\WsServerOpenSwoole\app;

use Bobo121278\WsServerOpenSwoole\AppInterface;
use OpenSwoole\WebSocket\Server;
use OpenSwoole\WebSocket\Frame;

class AppClientServer implements AppInterface
{
    /**
     * @var string
     */
    private $uniqueName;

    public function __construct(string $uniqueName)
    {
        $this->uniqueName = $uniqueName;
    }

    private $serverFd;

    function onMessage(Server $server, Frame $frame, array $usersList)
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
            $this->serverMsg($server, $frame, $usersList, $msg);
        } else {
            $this->clientMsg($server, $frame, $usersList, $msg);
        }

    }

    private function setServer($fd)
    {
        global $memory;
        $memory->set($this->uniqueName, ['data' => (string)$fd]);
    }

    private function getServer()
    {
        global $memory;
        return $memory->get($this->uniqueName)['data'];
    }

    private function removeServer()
    {
        global $memory;
        return $memory->del($this->uniqueName);
    }

    private function clientMsg(Server $server, Frame $frame, array $usersList, array $msg)
    {

        $msgToServer = [
            'msg' => $msg['msg'],
            'to' => $frame->fd
        ];
        $server->push($this->getServer(), $this->encodeMsg($msgToServer));

    }

    private function serverMsg(Server $server, Frame $frame, array $usersList, array $msg)
    {
        $this->serverFd = $frame->fd;

        if (
            array_key_exists('msg', $msg) &&
            array_key_exists($msg['to'], $usersList) &&
            array_key_exists('to', $msg)
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

    public function onOpen(): string
    {
        // TODO: Implement onOpen() method.
        return '';
    }
}