<?php


use WebSocket\Client;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once './vendor/autoload.php';
//$client = new Client("ws://192.168.0.13:9032/toall", ['timeout' => 60 * 5]);
//$client = new Client("ws://bot.test/toall", ['timeout' => 60 * 5]);
$client = new Client("ws://localhost:9032/toall", ['timeout' => 60*5]);
//$client = new Client("ws://20.203.223.233/toall", ['timeout' => 60*5]);
$time = time();
$lp = 0;
$strlen = 0;
$msgLpcheck = 0;
while (true) {
    $msg = $client->receive();
    $strlen += strlen($msg);
    $lp++;
    if ( (time()-$time)>= 2) {
        $elapsed = time() - $time;
        $speed = $lp / $elapsed;
        $lp = 0;
        $time = time();
        $transfer = $strlen / $elapsed;
        $strlen = 0;
        echo $speed . " msg/s " . formatBytes($transfer) . "\n";

    }
    $msg = explode(' ', $msg,2)[0];

    if($msgLpcheck == 0 || $msg == 1){
        $msgLpcheck = $msg;
    }else{
        $msgLpcheck++;
        if($msgLpcheck != $msg){
           // var_dump($msgLpcheck . ' ----  ' .$msg);
        }

    }
}

function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    // Uncomment one of the following alternatives
    // $bytes /= pow(1024, $pow);
     $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . ' ' . $units[$pow];
}