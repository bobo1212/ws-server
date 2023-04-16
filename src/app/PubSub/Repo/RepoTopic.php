<?php

namespace Bobo121278\WsServerOpenSwoole\app\PubSub\Repo;

use Bobo1212\SharedMemory\Memory as SharedMemory;
use Bobo1212\SharedMemory\MemoryException;
use Bobo1212\SharedMemory\MemoryExceptionNotFound;

class RepoTopic implements InterfaceTopic
{
    const MEMORY_KEY_TOPICS = 1000000001;
    private SharedMemory $sharedMemory;

    public function __construct(SharedMemory $sharedMemory)
    {
        $this->sharedMemory = $sharedMemory;
    }

    public function unSubscribeTopic(string $topicName, int $fd)
    {
        $this->sharedMemory->lock();
        $topics = $this->getTopics();
        if (!array_key_exists($topicName, $topics)) {
            $this->sharedMemory->unLock();
            return;
        }
        unset($topics[$topicName][$fd]);
        $this->sharedMemory->write(self::MEMORY_KEY_TOPICS, $topics);
        $this->sharedMemory->unLock();
    }

    public function subscribeTopic(string $topicName, int $fd)
    {
        $this->sharedMemory->lock();
        $topics = $this->getTopics();
        if (!array_key_exists($topicName, $topics)) {
            $this->sharedMemory->unLock();
            return;
        }
        $topics[$topicName][$fd] = $fd;
        $this->sharedMemory->write(self::MEMORY_KEY_TOPICS, $topics);
        $this->sharedMemory->unLock();
    }

    public function addTopic(string $topicName)
    {
        $this->sharedMemory->lock();
        $topics = $this->getTopics();
        if (array_key_exists($topicName, $topics)) {
            $this->sharedMemory->unLock();
            return;
        }
        $topics[$topicName] = [];
        $this->sharedMemory->write(self::MEMORY_KEY_TOPICS, $topics);
        $this->sharedMemory->unLock();
    }

    public function removeTopic(string $topicName)
    {
        $this->removeFromMemoryArray(self::MEMORY_KEY_TOPICS, $topicName);
    }

    private function removeFromMemoryArray(int $memoryKey, string $arrayKey)
    {
        $this->sharedMemory->lock();
        try {
            $array = $this->sharedMemory->read($memoryKey);
        } catch (MemoryExceptionNotFound $e) {
            $this->sharedMemory->unLock();
            return;
        }
        if (!is_array($array)) {
            $this->sharedMemory->unLock();
            return;
        }
        unset($array[$arrayKey]);
        $this->sharedMemory->write($memoryKey, $array);
        $this->sharedMemory->unLock();
    }

    public function getTopics(): array
    {
        try {
            $topics = $this->sharedMemory->read(self::MEMORY_KEY_TOPICS);
        } catch (MemoryExceptionNotFound $e) {
            $topics = [];
        }
        if (!is_array($topics)) {
            $topics = [];
        }
        return $topics;
    }

    /**
     * @throws MemoryException
     */
    public function getTopicUsers(string $topicName): array
    {
        $topics = $this->getTopics();
        if (!array_key_exists($topicName, $topics)) {
            return [];
        }
        return $topics[$topicName];
    }
}