<?php


use OpenSwoole\WebSocket\Server;
use OpenSwoole\Http\Request;
use OpenSwoole\WebSocket\Frame;

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

$ip = "127.0.0.1";
$port = 9032;

$server = new Server($ip, $port);


$server->on('WorkerStart', function ($serv) {
    require_once 'src/onWorkerStart/table.php';
    require_once 'src/onWorkerStart/loadApp.php';
    require_once 'src/onWorkerStart/on/onOpen.php';
    require_once 'src/onWorkerStart/on/onMessage.php';
    require_once 'src/onWorkerStart/on/onClose.php';
});
$server->on('Receive', function ($serv) {
    logMsg(LogLevel::INFO, 'onReceive!XXXXXXXXXXXX');
});
$server->on('BeforeReload', function ($serv) {
});
$server->on('AfterReload', function ($serv) {
});

$server->on("Start", function (Server $server) use ($ip, $port) {
    logMsg(LogLevel::INFO, 'OpenSwoole WebSocket Server is started at ws://' . $ip . ':' . $port);
});

$server->on('Open', function (Server $server, Request $request) {
    onOpen($server, $request);
});

$server->on('Message', function (Server $server, Frame $frame) {
    onMessage($server, $frame);
});

$server->on('Close', function (Server $server, int $fd) {
    onClose($server,$fd);
});

$server->on('Disconnect', function (Server $server, int $fd) {
    logMsg(LogLevel::INFO, 'connection disconnect: ' . $fd);
});

global $users;
global $uri;
global $memory;


function createUsersTable()
{
    global $users;
    // id = uri, dane = zserjalizowana lista userów
    $table = new \OpenSwoole\Table(1024);
    $table->column('users', \OpenSwoole\Table::TYPE_STRING, 1024 * 100);
    $table->create();
    $users = $table;
}

function getUsers(string $uri)
{
    global $users;
    if ($users->exists($uri)) {
        return json_decode($users->get($uri)['users'], true);
    }
    return [];
}

function setUser(string $uri, $fd)
{
    global $users;
    $usersList = getUsers($uri);
    $usersList[$fd] = [];
    $users->set($uri, ['users' => json_encode($usersList)]);
}

function createUriTable()
{
    global $uri;
    //id = fd, dane = uri
    $table = new \OpenSwoole\Table(1024);
    $table->column('uri', \OpenSwoole\Table::TYPE_STRING, 256);
    $table->create();
    $uri = $table;
}

function setUri(int $fd, string $requestUri)
{
    global $uri;
    $uri->set($fd, ['uri' => $requestUri]);
}

function getUri(int $fd)
{
    global $uri;
    return $uri->get($fd)['uri'];
}

function removeFromTable(int $fd)
{
    global $users;
    global $uri;
    $requestUri = getUri($fd);
    $uri->del($fd);
    $usersList = getUsers($requestUri);
    unset($usersList[$fd]);
    $users->set($requestUri, ['users' => json_encode($usersList)]);
}
function createMemoryTable()
{
    global $memory;
    //id = fd, dane = uri
    $table = new \OpenSwoole\Table(1024);
    $table->column('data', \OpenSwoole\Table::TYPE_STRING, 256);
    $table->create();
    $memory = $table;
}
createUsersTable();
createUriTable();
createMemoryTable();



$server->start();


