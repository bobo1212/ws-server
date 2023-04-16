<?php

namespace Bobo121278\WsServerOpenSwoole\app\PubSub\Repo;

use Bobo1212\SharedMemory\Memory as SharedMemory;
use Bobo1212\SharedMemory\MemoryException;
use Bobo1212\SharedMemory\MemoryExceptionNotFound;

class RepoProducer implements InterfaceProducer
{
    const MEMORY_KEY_PRODUCER = 1000000000;
    private SharedMemory $sharedMemory;

    public function __construct(SharedMemory $sharedMemory)
    {
        $this->sharedMemory = $sharedMemory;
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

    public function addProducer(int $fd)
    {
        $this->sharedMemory->lock();
        try {
            $servers = $this->sharedMemory->read(self::MEMORY_KEY_PRODUCER);
        } catch (MemoryExceptionNotFound $e) {
            $servers = [];
        }
        if (!is_array($servers)) {
            $servers = [];
        }
        $servers[$fd] = $fd;
        $this->sharedMemory->write(self::MEMORY_KEY_PRODUCER, $servers);
        $this->sharedMemory->unLock();
    }

    public function removeProducer(int $fd)
    {
        $this->removeFromMemoryArray(self::MEMORY_KEY_PRODUCER, (string)$fd);
    }

    public function getProducers(): array
    {
        try {
            $servers = $this->sharedMemory->read(self::MEMORY_KEY_PRODUCER);
        } catch (MemoryExceptionNotFound $e) {
            $servers = [];
        }
        if (!is_array($servers)) {
            $servers = [];
        }
        return $servers;
    }
}