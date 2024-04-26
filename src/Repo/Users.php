<?php

namespace Bobo1212\WsServerOpenSwoole\Repo;

use Bobo1212\SharedMemory\MemoryException;
use Exception;

class Users
{
    private \OpenSwoole\Table $users;

    public function __construct()
    {
        $usersColumnSite = 1024 * 100;
        $tableSize = 100;

        $table = new \OpenSwoole\Table($tableSize);
        $table->column('users', \OpenSwoole\Table::TYPE_STRING, $usersColumnSite);
        $table->create();
        $this->users = $table;
        $this->sem = sem_get(999);
        if (false === $this->sem) {
            throw new MemoryException('sem_get error');
        }
    }

    private function lock()
    {
        $lock = sem_acquire($this->sem);
        if (false === $lock) {
            throw new Exception('sem_acquire error');
        }
    }
    private function unLock(){
        $lock = sem_release($this->sem);
        if (false === $lock) {
            throw new Exception('sem_release error');
        }
    }
    public function getUsersByUri(string $uri): array
    {
        $users = $this->users->get($uri);
        if ($users) {
            return json_decode($users['users'], true);
        }
        return [];
    }

    public function addUser(string $uri, $fd): void
    {
        $this->lock();
        $users = $this->getUsersByUri($uri);
        if (in_array($fd, $users)) {
            $this->unLock();
            return;
        }
        $users[$fd] = [];
        $this->users->set($uri, ['users' => json_encode($users)]);
        $this->unLock();
    }

    public function removeUser(string $uri, int $fd): void
    {
        $this->lock();
        $users = $this->getUsersByUri($uri);
        unset($users[$fd]);
        $this->users->set($uri, ['users' => json_encode($users)]);
        $this->unLock();
    }
}