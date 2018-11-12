<?php

require_once __DIR__."/../bootstrap/web_init.php";

if (!(isset($_SESSION["login"]) && $_SESSION["login"] === true)) {
	header("Location: /login.php?ref=home&w=".urlencode(rstr(64)));
	exit;
}

$me   = BASEPATH."/config/me.cfg.tmp";
$file = BASEPATH."/config/token.cfg.tmp";
$stop = (int)file_get_contents(BASEPATH."/storage/stop.txt");
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
	$std["errno"] = 0;
	//$std["out"] = '{"ok":true,"result":{"url":"https://veno.site/webhook.php?hashd=40b4d020635683fc46d652f77f5613e60ad3f19e","has_custom_certificate":false,"pending_update_count":0,"last_error_date":1541121667,"last_error_message":"Wrong response from the webhook: 403 Forbidden","max_connections":40}}';
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


$pdo = \Sq\DB::pdo();
$st = $pdo->prepare("SELECT COUNT(1) FROM `users`;");
$st->execute();
$joinedMember = $st->fetch(PDO::FETCH_NUM)[0];



unset($st, $pdo);


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
				<td>Status Bot</td>
				<td>:</td>
				<td><?php print isset($webhook_url) ? ($webhook_url === "" ? "Offline" : "Online") : $status; ?></td>
			</tr>
			<tr>
				<td>Joined Members</td>
				<td>:</td>
				<td><?php print $joinedMember; ?> users (<a href="/show_members.php">View Members</a>)</td>
			</tr>
		</table>
		<table>
			<tr>
				<td colspan="2" align="center">
					<a href="change_bot_token.php?ref=home&w=<?php print urlencode(rstr(64)); ?>"><button>Edit Token Bot</button></a>
				</td>
				<td colspan="2" align="center">
					<a href="edit_point.php"><button>Edit Point</button></a>
					<a href="edit_social_media.php"><button>Edit Social Media</button></a>
					<a href="set_webhook.php?switch=<?php
						$c = isset($webhook_url) && $webhook_url === "";
						print $c ? "on" : "off";
					?>&ref=home&w=<?php print urlencode(rstr(64)); ?>"><button><?php print $c ? "Start Bot" : "Delete Webhook"; ?></button></a>
				</td>
			</tr>
		</table>
		<div>
			<p><b>Auto stop when the amount of members reached: </b><?php print $stop; ?> (<a href="edit_stop.php">Edit</a>)</p>
			<a href="edit_stop_message.php"><button>Edit Auto Stop Message</button></a>
		</div>
		<div style="margin-top: 20px;">
			<h2>Broadcast Mesasge to All Users</h2>
			<form action="broadcast.php" method="post">
				<textarea name="msgd"></textarea><br/>
				<input type="submit" name="submit" value="Send"/>
			</form>
		</div>
	</center>
</body>
</html>