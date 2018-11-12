<?php

require_once __DIR__."/../bootstrap/web_init.php";

if (!(isset($_SESSION["login"]) && $_SESSION["login"] === true)) {
	header("Location: /login.php?ref=home&w=".urlencode(rstr(64)));
	exit;
}

if (isset($_POST["msgd"], $_POST["submit"])) {
	if (is_string($_POST["msgd"]) && ($_POST["msgd"]=trim($_POST["msgd"])) !== "") {
		$msg = escapeshellarg($_POST["msgd"]);
		shell_exec("nohup /usr/bin/env php ".__DIR__."/bc.php {$msg} >> /dev/null 2>&1 &");
	}
}
