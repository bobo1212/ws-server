<?php

namespace Bobo121278\WsServerOpenSwoole\Repo;

class Memory
{

    private \OpenSwoole\Table $memory;

    public function __construct()
    {
        $table = new \OpenSwoole\Table(1024);
        $table->column('data', \OpenSwoole\Table::TYPE_STRING, 256);
        $table->create();
        $this->memory = $table;
    }

    public function set($kye, $value)
    {
        $this->memory->set($kye, ['data' => (string)$value]);
    }

    public function get($key): string
    {
        $ret = $this->memory->get($key);
        if ($ret) {
            return $ret['data'];
        }
        return '';
    }
    function del($key): void
    {
        $this->memory->del($key);
    }
}