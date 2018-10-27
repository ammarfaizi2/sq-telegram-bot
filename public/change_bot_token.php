<?php

require __DIR__."/../bootstrap/web_init.php";

if (!(isset($_SESSION["login"]) && $_SESSION["login"] === true)) {
	header("Location: /login.php?ref=home&w=".urlencode(rstr(64)));
	exit;
}

$file = BASEPATH."/config/token.cfg.tmp";

if (file_exists($file)) {
	$token = trim(file_get_contents($file));
	if (empty($token)) {
		$status = "empty_token";
	} else {
		$status = "token_exists";
	}
} else {
	$status = "empty_token";
}

?><!DOCTYPE html>
<html>
<head>
	<title>Ubah Token Bot</title>
</head>
<body>
	<center>
		<?php ?>
	</center>
</body>
</html>