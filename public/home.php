<?php

require_once __DIR__."/../bootstrap/web_init.php";

if (!(isset($_SESSION["login"]) && $_SESSION["login"] === true)) {
	header("Location: /login.php?ref=home&w=".urlencode(rstr(64)));
	exit;
}

$me   = BASEPATH."/config/me.cfg.tmp";
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


if ($status === "token_exists") {
	$std = curld("https://api.telegram.org/bot{$token}/getWebhookInfo");
	if ($std["errno"]) {
		$status = "Error: {$std['error']}";
	} else {
		$status = $std["out"];
		$std = json_decode($std["out"], true);
		if (isset(
			$std["ok"],
			$std["result"]["url"],
			$std["result"]["pending_update_count"]
		) && $std["ok"]) {
			$pending_update_count = $std["result"]["pending_update_count"];
			$webhook_url = $std["result"]["url"];
			unset($status, $std);
		}
	}
} else {
	$status = "Token is not set";
}


?><!DOCTYPE html>
<html>
<head>
	<title>Bot Panel</title>
	<style type="text/css">
		* {
			font-family: Arial;
		}
		.cgd {
			margin-top: 20px;
		}
		button {
			cursor: pointer;
		}
	</style>
</head>
<body>
	<center>
		<div class="cgd">
			<a href="/logout.php?w=<?php print urlencode(rstr(64)); ?>"><button>Logout</button></a>
		</div>
		<h1>Welcome Admin!</h1>
		<table>
			<tr>
				<td>Status Bot:</td>
				<td><?php print isset($webhook_url) ? ($webhook_url === "" ? "Offline" : "Online") : $status; ?></td>
			</tr>			
		</table>
		<table>
			<tr>
				<td colspan="2" align="center">
					<a href="change_bot_token.php?ref=home&w=<?php print urlencode(rstr(64)); ?>"><button>Ubah Token Bot</button></a>
				</td>
				<td colspan="2" align="center">
					<a href="set_webhook.php?switch=<?php
						$c = isset($webhook_url) && $webhook_url === "";
						print $c ? "on" : "off";
					?>&ref=home&w=<?php print urlencode(rstr(64)); ?>"><button><?php print $c ? "Start" : "Stop"; ?> Bot</button></a>
				</td>
			</tr>
		</table>
	</center>
</body>
</html>