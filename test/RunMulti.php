<?php

//$command = "nohup php test/Msg.php";
$command = "nohup php test/TopicReceive.php";

for($i=1;$i<=100;$i++) {
    $str = shell_exec($command . ' ' . $i . ' > out.log 2>&1 &');
    usleep(100);
}