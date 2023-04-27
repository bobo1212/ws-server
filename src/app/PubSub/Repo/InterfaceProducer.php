<?php

namespace Bobo1212\WsServerOpenSwoole\app\PubSub\Repo;

interface InterfaceProducer
{
    public function addProducer(int $fd);
    public function removeProducer(int $fd);
    public function getProducers():array;
}