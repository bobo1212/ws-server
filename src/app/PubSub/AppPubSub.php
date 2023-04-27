<?php

namespace Bobo121278\WsServerOpenSwoole\app\PubSub;

use Bobo1212\SharedMemory\Memory as SharedMemory;
use Bobo121278\WsServerOpenSwoole\app\PubSub\Repo\RepoTopic;
use Bobo121278\WsServerOpenSwoole\AppInterface;
use OpenSwoole\Http\Request;
use OpenSwoole\WebSocket\Frame;
use OpenSwoole\WebSocket\Server;

class AppPubSub implements AppInterface
{
    const MEMORY_KEY = 1;

    private RepoTopic $repoTopic;

    /**
     *
     */
    public function __construct()
    {
        $this->repoTopic = new RepoTopic(new SharedMemory(self::MEMORY_KEY));
    }

    public function signatureIsValid(array $msg): string
    {
        $siganature = $msg['signature'];
        unset($msg['signature']);
        $msgToSign = json_encode($msg);

        //$auth_signature = hash_hmac('sha256', $string_to_sign, $auth_secret, false);
        //hash_compare($hashed_value, $hashed_expected)
        return '';
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
        if ($msg['type'] == 'msg') {
            $this->onMessageMsg($server, $msg);
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
            return;
        }
        if ($msg['type'] == 'admin') {
            $server->push($frame->fd, $this->encodeMsg([
                'topics' => $this->repoTopic->getTopics()
            ]));
        }
    }

    public function onClose(Server $server, int $fd)
    {
        $this->repoTopic->unSubscribeAllTopics($fd);
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
        if (array_key_exists('authorization', $request->header)) {
            var_dump($request->header['authorization']);
        }
        if (is_array($request->get) && array_key_exists('token', $request->get)) {
            var_dump($request->get['token']);
        }
        return '';
    }

    private function onMessageMsg(Server $server, array $msg)
    {
        if (
            array_key_exists('msg', $msg) &&
            array_key_exists('topic', $msg)
        ) {
            $users = $this->repoTopic->getTopicUsers($msg['topic']);
            foreach ($users as $fd => $userData) {
                if ($server->isEstablished($fd)) {
                    $ret = $server->push($fd, $this->encodeMsg([
                        'msg' => $msg['msg']
                    ]));
                } else {
                    $this->repoTopic->unSubscribeAllTopics($fd);
                }
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