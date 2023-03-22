<?php

namespace Bobo121278\WsServerOpenSwoole\Repo;

class Uri
{
    private \OpenSwoole\Table $uri;

    public function __construct()
    {
        $uriColumnSite = 1024;
        $tableSize = $uriColumnSite * 10;
        $table = new \OpenSwoole\Table($tableSize);
        $table->column('uri', \OpenSwoole\Table::TYPE_STRING, $uriColumnSite);
        $table->create();
        $this->uri = $table;
    }

    public function setUri(int $fd, string $uri): void
    {
        $this->uri->set($fd, ['uri' => $uri]);
    }

    function getUri(int $fd): string
    {
        return $this->uri->get($fd)['uri'];
    }

    function removeUri(int $fd): void
    {
        $this->uri->del($fd);
    }
}