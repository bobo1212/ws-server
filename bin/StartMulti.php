<?php


for ($i = 0; $i <= 100; $i++) {


    run(
        'php /media/grzegorz/DATA/projekty/php/wsServerOpenSwoole/bin/SpeedTestConsumer.php',
        '/media/grzegorz/DATA/projekty/php/wsServerOpenSwoole/out.log'
    );

}

function run($command, $outputFile = '/dev/null') {
    $processId = shell_exec(sprintf(
        '%s > %s 2>&1 & echo $!',
        $command,
        $outputFile
    ));

    print_r("processID of process in background is: " . $processId);
}