<?php

namespace Bobo121278\WsServerOpenSwoole\app\PubSub;

use Bobo1212\SharedMemory\Memory as SharedMemory;
use Bobo1212\SharedMemory\MemoryException;
use Bobo1212\SharedMemory\MemoryExceptionNotFound;
use Bobo121278\WsServerOpenSwoole\app\PubSub\Repo\RepoProducer;
use Bobo121278\WsServerOpenSwoole\app\PubSub\Repo\RepoTopic;
use Bobo121278\WsServerOpenSwoole\AppInterface;
use OpenSwoole\Http\Request;
use OpenSwoole\WebSocket\Frame;
use OpenSwoole\WebSocket\Server;

class AppPubSub implements AppInterface
{
    const MEMORY_KEY_SERVER = 1000000000;
    const MEMORY_KEY = 1;
    /**
     * @var SharedMemory
     */
    private SharedMemory $sharedMemory;
    private RepoTopic $repoTopic;
    private RepoProducer $repoProducer;

    /**
     *
     */
    public function __construct()
    {
        $this->sharedMemory = new SharedMemory(self::MEMORY_KEY);
        $this->repoTopic = new RepoTopic($this->sharedMemory);
        $this->repoProducer = new RepoProducer($this->sharedMemory);
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
        if ($msg['type'] == 'producer') {
            $this->onMessageAddProducer($frame->fd);
            return;
        }
        if ($msg['type'] == 'msg') {
            $this->onMessageProducerMsg($server, $msg);
            return;
        }
        if ($msg['type'] == 'subscribe') {
            $this->onMessageSubscribe($frame->fd, $msg);
            return;
        }
        if ($msg['type'] == 'unsubscribe') {
            $this->onMessageUnsubscribe($frame->fd, $msg);
            return;
        }
        if ($msg['type'] == 'create_topic') {
            $this->onMessageCreateTopic($frame->fd, $msg);
            return;
        }
        if ($msg['type'] == 'delete_topic') {
            $this->onMessageDeleteTopic($frame->fd, $msg);
        }
        if ($msg['type'] == 'admin') {
            $server->push($frame->fd, $this->encodeMsg([
                'producers' => $this->repoProducer->getProducers(),
                'topics' => $this->repoTopic->getTopics()
            ]));
        }
    }

    public function onClose(Server $server, int $fd)
    {
        $serverFd = $this->repoProducer->getProducers();
        if (array_key_exists($fd, $serverFd)) {
            $this->repoProducer->removeProducer($fd);
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

    private function onMessageAddProducer($fd)
    {
        $this->repoProducer->addProducer($fd);
    }

    private function onMessageProducerMsg(Server $server, array $msg)
    {
        if (
            array_key_exists('msg', $msg) &&
            array_key_exists('topic', $msg)
        ) {
            $users = $this->repoTopic->getTopicUsers($msg['topic']);
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
            $this->repoTopic->subscribeTopic($msg['topic'], $fd);
        }
    }

    private function onMessageUnsubscribe(int $fd, array $msg)
    {
        if (array_key_exists('topic', $msg)) {
            $this->repoTopic->unSubscribeTopic($msg['topic'], $fd);
        }
    }

    private function onMessageCreateTopic(int $fd, array $msg)
    {
        if (array_key_exists('topic', $msg)) {
            $this->repoTopic->addTopic($msg['topic'], $fd);
        }
    }

    private function onMessageDeleteTopic(int $fd, array $msg)
    {
        if (array_key_exists('topic', $msg)) {
            $this->repoTopic->removeTopic($msg['topic']);
        }
    }
}