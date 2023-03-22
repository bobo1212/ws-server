<?php

namespace Bobo121278\WsServerOpenSwoole\Repo;

class Users
{
    private \OpenSwoole\Table $users;

    public function __construct()
    {
        $usersColumnSite = 1024 * 10;
        $tableSize = 10;

        $table = new \OpenSwoole\Table($tableSize);
        $table->column('users', \OpenSwoole\Table::TYPE_STRING, $usersColumnSite);
        $table->create();
        $this->users = $table;
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
        $users = $this->getUsersByUri($uri);
        if (in_array($fd, $users)) {
            return;
        }
        $users[$fd] = [];
        $this->users->set($uri, ['users' => json_encode($users)]);
    }

    public function removeUser(string $uri, int $fd): void
    {
        $users = $this->getUsersByUri($uri);
        unset($users[$fd]);
        $this->users->set($uri, ['users' => json_encode($users)]);
    }

}