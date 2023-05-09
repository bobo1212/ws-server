<?php

use Bobo1212\WsServerOpenSwoole\app\AppAdmin;
use Bobo1212\WsServerOpenSwoole\app\AppToAll;
use Bobo1212\WsServerOpenSwoole\app\PubSub\AppPubSub;


global $appConfig;

$appConfig = [
    '/' => new  AppToAll(),
    '/admin' => new  AppAdmin(),
    '/topics' => new AppPubSub(),
];
