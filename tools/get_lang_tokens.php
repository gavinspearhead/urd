<?php


include '../functions/defines.php';
include '../functions/lang/english.php';

$keys = array_keys($LN);


foreach ($keys as $key) {
    $s1 = 'LN_' . $key;
    $s2 = 'LN\[' . '[\'\\"]' . $key . '[\'\\"]' . ']'; 
    $cmd = "egrep \"($s1)|($s2)\" ../* -r --exclude-dir=lang";
    unset($out);
    exec($cmd, $out, $rv);
    if (count($out) == 0) {
        echo $key . ' ' . count($out) . "\n";
    }
}


