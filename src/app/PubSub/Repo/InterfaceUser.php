<?php

namespace Bobo1212\WsServerOpenSwoole\app\PubSub\Repo;

use Bobo1212\SharedMemory\MemoryExceptionNotFound;

interface InterfaceUser
{
    public function addUser(int $fd, $data);
    public function getUsers(): array;
    public function getUser(int $fd): array;
    public function removeUser(int $fd):void;
}