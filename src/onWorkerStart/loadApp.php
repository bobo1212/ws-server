<?php
require_once SERVER_DIR . '/vendor/autoload.php';
require_once SERVER_DIR . '/src/AppInterface.php';
require_once SERVER_DIR . '/src/app/AppAdmin.php';
require_once SERVER_DIR . '/src/app/AppToAll.php';
require_once SERVER_DIR . '/src/app/AppClientServer.php';
require_once SERVER_DIR . '/src/app/PubSub/AppPubSub.php';
require_once SERVER_DIR . '/src/app/PubSub/Repo/InterfaceTopic.php';
require_once SERVER_DIR . '/src/app/PubSub/Repo/RepoTopic.php';
require_once SERVER_DIR . '/src/app/PubSub/Repo/InterfaceProducer.php';
require_once SERVER_DIR . '/src/app/PubSub/Repo/RepoProducer.php';

global $appConfig;
$appConfig = [
    '/admin' => new  \Bobo121278\WsServerOpenSwoole\app\AppAdmin(),
    '/toall' => new  \Bobo121278\WsServerOpenSwoole\app\AppToAll(),
    '/ai' => new \Bobo121278\WsServerOpenSwoole\app\AppClientServer('unikalnaNazwa_jsdfh7234'),
    '/topics' => new \Bobo121278\WsServerOpenSwoole\app\PubSub\AppPubSub(),
];
