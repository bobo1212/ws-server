<?php

namespace Bobo121278\WsServerOpenSwoole\app;

use Bobo1212\SharedMemory\Memory as SharedMemory;
use Bobo1212\SharedMemory\MemoryException;
use Bobo1212\SharedMemory\MemoryExceptionNotFound;
use Bobo121278\WsServerOpenSwoole\AppInterface;
use OpenSwoole\Http\Request;
use OpenSwoole\WebSocket\Frame;
use OpenSwoole\WebSocket\Server;

class AppPubSub implements AppInterface
{
    const MEMORY_KEY_SERVER = 1000000000;
    const MEMORY_KEY_TOPICS = 1000000001;
    /**
     * @var SharedMemory
     */
    private SharedMemory $sharedMemory;

    /**
     *
     */
    public function __construct()
    {
        $this->sharedMemory = new SharedMemory('1');
    }

    public function getAppName(): string
    {
        return 'Publish Subscribe';
    }

    function onMessage(Server $server, Frame $frame, array $users)
    {
        $msg = $this->decodeMsg($frame->data);
        if (empty($msg)) {
            return;
        }
        if (!array_key_exists('type', $msg)) {
            return;
        }
        if ($msg['type'] == 'server') {
            $this->onMessageSetServer($frame->fd);
            var_dump($this->sharedMemory->read(self::MEMORY_KEY_SERVER));
            return;
        }
        if ($msg['type'] == 'msg') {
            $this->onMessageServerMsg($server, $msg);
            return;
        }
        if ($msg['type'] == 'subscribe') {
            $this->onMessageSubscribe($frame->fd, $msg);
            var_dump($this->sharedMemory->read(self::MEMORY_KEY_TOPICS));
            return;
        }
        if ($msg['type'] == 'unsubscribe') {
            $this->onMessageUnsubscribe($frame->fd, $msg);
            return;
        }
        if ($msg['type'] == 'create_topic') {
            $this->onMessageCreateTopic($frame->fd, $msg);
            var_dump($this->sharedMemory->read(self::MEMORY_KEY_TOPICS));
            return;
        }
        if ($msg['type'] == 'delete_topic') {
            $this->onMessageDeleteTopic($frame->fd, $msg);
        }
    }

    /**
     * @throws MemoryException
     * @throws MemoryExceptionNotFound
     */
    private function getServer(): int
    {
        return $this->sharedMemory->read(self::MEMORY_KEY_SERVER);
    }

    private function removeServer()
    {
        $this->sharedMemory->write(self::MEMORY_KEY_SERVER, null);
    }

    /**
     * @throws MemoryException
     */
    private function addTopic(string $topicName)
    {
        $this->sharedMemory->lock();
        try {
            $topics = $this->sharedMemory->read(self::MEMORY_KEY_TOPICS);
        } catch (MemoryExceptionNotFound $e) {
            $topics = [];
        }
        if (!is_array($topics)) {
            $topics = [];
        }
        $topics[$topicName] = [];
        $this->sharedMemory->write(self::MEMORY_KEY_TOPICS, $topics);
        $this->sharedMemory->unLock();
    }

    /**
     * @throws MemoryException
     */
    private function removeTopic(string $topicName)
    {
        $this->sharedMemory->lock();
        try {
            $topics = $this->sharedMemory->read(self::MEMORY_KEY_TOPICS);
        } catch (MemoryExceptionNotFound $e) {
            return;
        }
        if (!is_array($topics)) {
            return;
        }
        unset($topics[$topicName]);
        $this->sharedMemory->write(self::MEMORY_KEY_TOPICS, $topics);
        $this->sharedMemory->unLock();
    }

    /**
     * @throws MemoryException
     */
    private function getTopicUsers(string $topicName)
    {
        $this->sharedMemory->lock();
        try {
            $topics = $this->sharedMemory->read(self::MEMORY_KEY_TOPICS);
        } catch (MemoryExceptionNotFound $e) {
            return [];
        }
        if (!is_array($topics)) {
            return [];
        }
        if (!array_key_exists($topicName, $topics)) {
            return [];
        }
        return $topics[$topicName];
    }

    /**
     * @throws MemoryException
     */
    private function subscribeTopic(string $topicName, int $fd)
    {
        $this->sharedMemory->lock();
        try {
            $topics = $this->sharedMemory->read(self::MEMORY_KEY_TOPICS);
        } catch (MemoryExceptionNotFound $e) {
            $topics = [];
        }
        if (!is_array($topics)) {
            $topics = [];
        }
        if (!array_key_exists($topicName, $topics)) {
            $this->sharedMemory->unLock();
            return;
        }
        $topics[$topicName][$fd] = $fd;
        $this->sharedMemory->write(self::MEMORY_KEY_TOPICS, $topics);
        $this->sharedMemory->unLock();
    }

    /**
     * @throws MemoryException
     */
    private function unSubscribeTopic(string $topicName, int $fd)
    {
        $this->sharedMemory->lock();
        try {
            $topics = $this->sharedMemory->read(self::MEMORY_KEY_TOPICS);
        } catch (MemoryExceptionNotFound $e) {
            return;
        }
        if (!is_array($topics)) {
            return;
        }
        unset($topics[$topicName][$fd]);
        $this->sharedMemory->write(self::MEMORY_KEY_TOPICS, $topics);
        $this->sharedMemory->unLock();
    }

    private function clientMsg(Server $server, Frame $frame, array $msg)
    {
        $msgToServer = [
            'msg' => $msg['msg'],
            'to' => $frame->fd
        ];
        $server->push($this->getServer(), $this->encodeMsg($msgToServer));
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

    public function onOpen(Server $server, Request $request): string
    {
        return '';
    }

    private function onMessageSetServer($fd)
    {
        $this->sharedMemory->write(self::MEMORY_KEY_SERVER, $fd);
    }

    private function onMessageServerMsg(Server $server, array $msg)
    {
        if (
            array_key_exists('msg', $msg) &&
            array_key_exists('topic', $msg)
        ) {
            $users = $this->getTopicUsers($msg['topic']);
            foreach ($users as $fd => $userData) {
                $server->push($fd, $this->encodeMsg([
                    'msg' => $msg['msg']
                ]));
            }
        }
    }

    private function onMessageSubscribe(int $fd, array $msg)
    {
        if (array_key_exists('topic', $msg)) {
            $this->subscribeTopic($msg['topic'], $fd);
        }
    }

    private function onMessageUnsubscribe(int $fd, array $msg)
    {
        if (array_key_exists('topic', $msg)) {
            $this->unSubscribeTopic($msg['topic'], $fd);
        }
    }

    private function onMessageCreateTopic(int $fd, array $msg)
    {
        if (array_key_exists('topic', $msg)) {
            $this->addTopic($msg['topic'], $fd);
        }
    }

    private function onMessageDeleteTopic(int $fd, array $msg)
    {
        if (array_key_exists('topic', $msg)) {
            $this->removeTopic($msg['topic']);
        }
    }
}