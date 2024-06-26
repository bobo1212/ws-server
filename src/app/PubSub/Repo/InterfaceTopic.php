<?php

namespace Bobo1212\WsServerOpenSwoole\app\PubSub\Repo;

interface InterfaceTopic
{
    public function unSubscribeTopic(string $topicName, int $fd);
    public function unSubscribeAllTopics(int $fd);
    public function subscribeTopic(string $topicName, int $fd);
    public function addTopic(string $topicName);
    public function removeTopic(string $topicName);
    public function getTopics():array;
    public function getTopicUsers(string $topicName):array;
}