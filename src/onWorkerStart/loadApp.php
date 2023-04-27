<?php

use Bobo1212\WsServerOpenSwoole\app\AppAdmin;
use Bobo1212\WsServerOpenSwoole\app\AppClientServer;
use Bobo1212\WsServerOpenSwoole\app\AppToAll;
use Bobo1212\WsServerOpenSwoole\app\PubSub\AppPubSub;

require_once SERVER_DIR . '/vendor/autoload.php';
global $appConfig;

$appConfig = [
    '/admin' => new  AppAdmin(),
    '/toall' => new  AppToAll(),
    '/ai' => new AppClientServer('unikalnaNazwa_jsdfh7234'),
    '/topics' => new AppPubSub(),
];
