<?php

namespace Bobo1212\WsServerOpenSwoole\app\PubSub\Repo;

use Bobo1212\SharedMemory\Memory as SharedMemory;
use Bobo1212\SharedMemory\MemoryExceptionNotFound;

class RepoUser implements InterfaceUser
{
    const MEMORY_KEY_USER = 1000000002;
    private SharedMemory $sharedMemory;

    public function __construct(SharedMemory $sharedMemory)
    {
        $this->sharedMemory = $sharedMemory;
    }

    public function addUser(int $fd, $data)
    {
        $this->sharedMemory->lock();
        $users = $this->getUsers();
        $users[$fd] = $data;
        $this->sharedMemory->write(self::MEMORY_KEY_USER, $users);
        $this->sharedMemory->unLock();
    }

    public function getUsers(): array
    {
        try {
            $users = $this->sharedMemory->read(self::MEMORY_KEY_USER);
        } catch (MemoryExceptionNotFound $e) {
            $users = [];
        }
        if (!is_array($users)) {
            $users = [];
        }
        return $users;
    }

    public function getUser(int $fd): array
    {
        $users = $this->getUsers();
        if (array_key_exists($fd, $users)) {
            return $users[$fd];
        }
        return [];
    }

    public function removeUser(int $fd):void
    {
        $this->removeFromMemoryArray($fd, self::MEMORY_KEY_USER);
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
}