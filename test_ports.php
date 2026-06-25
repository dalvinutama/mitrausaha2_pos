<?php
$ports = [465, 587];
foreach ($ports as $port) {
    $fp = @fsockopen('smtp.gmail.com', $port, $errno, $errstr, 5);
    if (!$fp) {
        echo "PORT $port IS BLOCKED ($errstr)\n";
    } else {
        echo "PORT $port IS OPEN\n";
        fclose($fp);
    }
}
