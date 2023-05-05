<?php

namespace Bobo1212\WsServerOpenSwoole\app\PubSub;

use Bobo1212\SharedMemory\Memory as SharedMemory;
use Bobo1212\WsServerOpenSwoole\app\PubSub\Repo\RepoTopic;
use Bobo1212\WsServerOpenSwoole\app\PubSub\Repo\RepoUser;
use Bobo1212\WsServerOpenSwoole\AppInterface;
use OpenSwoole\Http\Request;
use OpenSwoole\WebSocket\Frame;
use OpenSwoole\WebSocket\Server;

class AppPubSub implements AppInterface
{
    private RepoTopic $repoTopic;
    private RepoUser $repoUser;

    /**
     *
     */
    public function __construct()
    {
        $this->repoTopic = new RepoTopic(new SharedMemory(RepoTopic::MEMORY_KEY_TOPICS));
        $this->repoUser = new RepoUser(new SharedMemory(RepoUser::MEMORY_KEY_USER));
    }

    public function getAppName(): string
    {
        return 'Publish Subscribe';
    }

    function onMessage(Server $server, Frame $frame)
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
                'topics' => $this->repoTopic->getTopics(),
            ]));
        }
    }

    public function onClose(Server $server, int $fd)
    {
        $this->repoTopic->unSubscribeAllTopics($fd);
        $this->repoUser->removeUser($fd);
    }

    private function decodeMsg(string $msg): array
    {
        $msg = @json_decode($msg, true,2);
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
//        if (!array_key_exists('authorization', $request->header)) {
//            logMsg(\LogLevel::INFO, 'Authorization header is missing ' . $request->server['remote_addr'] . $request->server['request_uri']);
//            $server->disconnect($request->fd,Server::WEBSOCKET_CLOSE_NORMAL,'Authorization header is missing');
//            return;
//        }
//        $authorizationHeader = trim($request->header['authorization']);
//        $authorizationType = substr($authorizationHeader, 0, 7);
//        $authorizationType = strtolower($authorizationType);
//        if ('bearer ' != $authorizationType) {
//            logMsg(\LogLevel::INFO, 'Authorization type not supported ' . $request->server['remote_addr'] . $request->server['request_uri']);
//            $server->disconnect($request->fd,Server::WEBSOCKET_CLOSE_NORMAL,'Authorization type not supported');
//        }
//        $token = substr($authorizationHeader, 7);
        $this->repoUser->addUser($request->fd,[]);
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