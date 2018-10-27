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

if ($status === "empty_token") {
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<title>Error</title>
	</head>
	<body>
		<center>
			<h1>Tidak dapata menyalakan bot: Token belum diset</h1>
			<a href="change_bot_token.php">Set Token Bot</a>
		</center>
	</body>
	</html>
	<?php
	exit;
}


if (isset($_GET["switch"])) {
	if ($_GET["switch"] === "on") {
		$std = curld("https://api.telegram.org/bot{$token}/setWebhook?url=".urlencode(WEBHOOK_URL));
		if ($std["errno"]) {
			$status = "Error: {$std['error']}";
		} else {
			$status = $std["out"];
			$std = json_decode($std["out"], true);
			if (isset(
				$std["ok"],
				$std["result"],
				$std["description"]
			) && $std["ok"] && $std["result"] &&
			($std["description"] === "Webhook was set" ||
			$std["description"] === "Webhook is already set")) {
				?><!DOCTYPE html>
				<html>
				<head>
					<title>Success</title>
					<script type="text/javascript">
						alert("Success!");
						window.location = "/home.php?ref=set_webhook_ok&w=<?php print urlencode(rstr(64)); ?>";
					</script>
				</head>
				<body>
					<h1>Success!</h1>
				</body>
				</html><?php
				exit;
			}
		}
		var_dump($std);
	}
}
