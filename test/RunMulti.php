<?php

$command = "nohup php test/Msg.php";

for($i=0;$i<986;$i++) {
    $str = shell_exec($command . ' > out.log 2>&1 &');
    usleep(100);
}