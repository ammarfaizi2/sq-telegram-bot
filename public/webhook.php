<?php

require __DIR__."/../bootstrap/init.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

	if (!file_exists(BASEPATH."/config/token.cfg.tmp")) {
		http_response_code(501);
		exit;
	}

	defined("BOT_TOKEN") or define("BOT_TOKEN", trim(file_get_contents(BASEPATH."/config/token.cfg.tmp")));

	if (isset($_GET["hashd"]) && sha1(BOT_TOKEN) === $_GET["hashd"]) {
		http_response_code(200);
		$input = escapeshellarg(urlencode(file_get_contents("php://input")));
		$std = shell_exec("nohup /usr/bin/env php ".BASEPATH."/bin/sq {$input} >> ".BASEPATH."/logs/webhook.log 2>&1 &");
		exit;
	}

}

http_response_code(403);
