<?php
$fp = @fsockopen('ssl://smtp.gmail.com', 465, $errno, $errstr, 5);
if (!$fp) {
    echo "ERROR 465: $errstr ($errno)\n";
} else {
    echo "CONNECTED 465\n";
    fclose($fp);
}

$fp2 = @fsockopen('tcp://smtp.gmail.com', 587, $errno, $errstr, 5);
if (!$fp2) {
    echo "ERROR 587: $errstr ($errno)\n";
} else {
    echo "CONNECTED 587\n";
    fclose($fp2);
}
