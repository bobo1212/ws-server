<?php
require_once 'src/AppInterface.php';
require_once 'src/app/AppAdmin.php';
require_once 'src/app/AppToAll.php';
require_once 'src/app/AppClientServer.php';

global $appConfig;
$appConfig = [
    '/admin' => new  \Bobo121278\WsServerOpenSwoole\app\AppAdmin(),
    '/toall' => new  \Bobo121278\WsServerOpenSwoole\app\AppToAll(),
    '/ai' => new \Bobo121278\WsServerOpenSwoole\app\AppClientServer('unikalnaNazwa_jsdfh7234'),
];
