<?php

if (!isset($argv[1]))
	die ("need parameter for file to add x to\n");


$f = file_get_contents($argv[1]);

$lines = explode ("\n", $f);

$l2 = "";

foreach ($lines as $line) {
	if (preg_match('/^[$]LN/', $line) >= 1) {
		$t = preg_replace ("/=\s*'/", "= 'x", $line);
		$t = preg_replace ('/=\s*"/', '= "x', $t);
		$l2[] = $t;
	} else
		$l2[]=$line;
}

foreach ($l2 as $l)
	echo $l . "\n";


?>
