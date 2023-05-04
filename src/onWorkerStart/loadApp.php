<?php

use Bobo1212\WsServerOpenSwoole\app\AppAdmin;
use Bobo1212\WsServerOpenSwoole\app\AppToAll;
use Bobo1212\WsServerOpenSwoole\app\PubSub\AppPubSub;

require_once SERVER_DIR . '/vendor/autoload.php';
global $appConfig;

$appConfig = [
    '/' => new  AppToAll(),
    '/admin' => new  AppAdmin(),
    '/topics' => new AppPubSub(),
];
