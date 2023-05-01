<?php


use OpenSwoole\WebSocket\Server;
use OpenSwoole\Http\Request;
use OpenSwoole\WebSocket\Frame;


define('SERVER_DIR', dirname(__DIR__));


class LogLevel
{
    const EMERGENCY = 'emergency';
    const ALERT = 'alert';
    const CRITICAL = 'critical';
    const ERROR = 'error';
    const WARNING = 'warning';
    const NOTICE = 'notice';
    const INFO = 'info';
    const DEBUG = 'debug';
}

function logMsg($level, $message, array $context = array())
{
    echo '[' . date('Y-m-d H:i:s') . '][' . $level . '] ' . $message . "\n";
}


$config = include(SERVER_DIR . '/config/config.php');
$ip = $config['ip'];
$port = $config['port'];
$server = new Server($ip, $port, OpenSwoole\Server::POOL_MODE);

$serverOptions = include(SERVER_DIR . '/config/serverOptions.php');
$server->set($serverOptions);

$server->on('WorkerStart', function (OpenSwoole\Server $server, int $workerId) {

    global $argv;

    if ($server->taskworker) {
        $name = 'task worker';
    } else {
        $name = 'event worker';
    }

    if ($workerId >= $server->setting['worker_num']) {
        logMsg(LogLevel::INFO, 'Start task worker ' . $workerId . ' ' . $name);
        OpenSwoole\Util::setProcessName("php {$argv[0]} task worker");
    } else {
        logMsg(LogLevel::INFO, 'Start event worker ' . $workerId . ' ' . $name);
        OpenSwoole\Util::setProcessName("php {$argv[0]} event worker");
    }

    require_once SERVER_DIR . '/src/onWorkerStart/loadApp.php';
    require_once SERVER_DIR . '/src/onWorkerStart/on/onOpen.php';
    require_once SERVER_DIR . '/src/onWorkerStart/on/onMessage.php';
    require_once SERVER_DIR . '/src/onWorkerStart/on/onClose.php';
});
$server->on('Receive', function ($serv) {
    logMsg(LogLevel::INFO, 'onReceive');
});
$server->on('BeforeReload', function ($serv) {
});
$server->on('AfterReload', function ($serv) {
});

$server->on("Start", function (Server $server) use ($ip, $port) {
    logMsg(LogLevel::INFO, 'WebSocket server is started at ws://' . $ip . ':' . $port);
});

$server->on('Open', function (Server $server, Request $request) {
    onOpen($server, $request);
});
$server->on('Request', function (OpenSwoole\Http\Request $request, OpenSwoole\Http\Response $response) use ($server) {

    $info = [];
    $response->end('<h1>Hello World! Openswolle</h1>');
//    foreach ($server->getClientList() as $client){
//        if($request->fd == $client){
//            continue;
//        }
//        $info[] =$client; /
//        $server->push($client,'www');
//    }
//
//    $response->end('<h1>Hello World! Openswolle</h1>'.json_encode($info));
});
$server->on('Message', function (Server $server, Frame $frame) {
    onMessage($server, $frame);
});

$server->on('Close', function (Server $server, int $fd) {
    onClose($server, $fd);
});

$server->on('Disconnect', function (Server $server, int $fd) {
    logMsg(LogLevel::INFO, 'connection disconnect: ' . $fd);
});
$server->on('Task', function (OpenSwoole\Server $server, $task_id, $reactorId, $data) {
    echo "Task Worker Process received data";
    echo "#{$server->worker_id}\tonTask: [PID={$server->worker_pid}]: task_id=$task_id, data_len=" . strlen($data) . "." . PHP_EOL;
    $server->finish($data);
});


require_once SERVER_DIR . '/src/Repo/Users.php';
require_once SERVER_DIR . '/src/Repo/Uri.php';
require_once SERVER_DIR . '/src/Repo/Memory.php';

global $users;
global $uri;
global $memory;

$memory = new \Bobo1212\WsServerOpenSwoole\Repo\Memory();
$users = new Bobo1212\WsServerOpenSwoole\Repo\Users();
$uri = new \Bobo1212\WsServerOpenSwoole\Repo\Uri();

function getUsers(string $uri)
{
    /* @var $users Bobo1212\WsServerOpenSwoole\Repo\Users */
    global $users;
    return $users->getUsersByUri($uri);
}

function setUser(string $uri, int $fd)
{
    /* @var $users Bobo1212\WsServerOpenSwoole\Repo\Users */
    global $users;
    $users->addUser($uri, $fd);
}


function setUri(int $fd, string $fdUri)
{
    /* @var $uri \Bobo1212\WsServerOpenSwoole\Repo\Uri() */
    global $uri;
    $uri->setUri($fd, $fdUri);
}

function getUri(int $fd)
{
    /* @var $uri \Bobo1212\WsServerOpenSwoole\Repo\Uri() */
    global $uri;
    return $uri->getUri($fd);
}

function removeFromTable(int $fd)
{
    /* @var $uri \Bobo1212\WsServerOpenSwoole\Repo\Uri() */
    global $uri;
    $fdUri = $uri->getUri($fd);
    $uri->removeUri($fd);
    /* @var $users Bobo1212\WsServerOpenSwoole\Repo\Users */
    global $users;
    $users->removeUser($fdUri, $fd);
}


function memorySet($k, $v)
{
    /* @var $memory Bobo1212\WsServerOpenSwoole\Repo\Memory */
    global $memory;
    $memory->set($k, $v);
}

function memoryGet($k): string
{
    /* @var $memory Bobo1212\WsServerOpenSwoole\Repo\Memory */
    global $memory;
    return $memory->get($k);
}


$server->start();


