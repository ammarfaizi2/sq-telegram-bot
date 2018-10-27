<?php

require __DIR__."/../bootstrap/init.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$input = escapeshellarg(urlencode(file_get_contents("php://input")));
	shell_exec("nohup /usr/bin/env php ".BASEPATH."/bin/sq {$input} >> ".BASEPATH."/logs/webhook.log 2>&1 &");
}
