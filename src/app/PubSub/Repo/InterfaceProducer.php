<?php

namespace Bobo121278\WsServerOpenSwoole\app\PubSub\Repo;

interface InterfaceProducer
{
    public function addProducer(int $fd);
    public function removeProducer(int $fd);
    public function getProducers():array;
}