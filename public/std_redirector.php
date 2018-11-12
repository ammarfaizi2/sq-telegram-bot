<?php

require __DIR__."/../bootstrap/init.php";

if (isset($_GET["id"], $_GET["to"])) {

	$fp = BASEPATH."/storage/redirector";

	switch ($_GET["to"]) {
		case "telegram_channel":
				$task = 2;
				if (file_exists($fp = $fp."/telegram_channel.txt")) {
					$fp = file_get_contents($fp);
				} else {
					$fp = null;
				}
			break;
		case "telegram_sponsor":
				$fp = "https://t.me/AirdropDetective";
			break;
		// case "twitter":
		// 		$task = 3;
		// 		if (file_exists($fp = $fp."/twitter.txt")) {
		// 			$fp = file_get_contents($fp);
		// 		} else {
		// 			$fp = null;
		// 		}
		// 	break;
		// case "facebook":
		// 		$task = 4;
		// 		if (file_exists($fp = $fp."/facebook.txt")) {
		// 			$fp = file_get_contents($fp);
		// 		} else {
		// 			$fp = null;
		// 		}
		// 	break;
		// case "medium";
		// 		$task = 5;
		// 		if (file_exists($fp = $fp."/medium.txt")) {
		// 			$fp = file_get_contents($fp);
		// 		} else {
		// 			$fp = null;
		// 		}
		// 	break;			
		default:
				header("Content-Type: text/plain");
				http_response_code(403);
				print "403 Forbidden";
				exit(0);
			break;
	}

	if (filter_var($fp, FILTER_VALIDATE_URL)) {
		if (!isset($task)) {
			header("Content-Type: text/plain");
			print "\$task is not defined!";
			exit(0);
		}
		if (file_exists(BASEPATH."/storage/task_cache/{$_GET['id']}_std")) {
			$d = json_decode(file_get_contents(BASEPATH."/storage/task_cache/{$_GET['id']}_std"), true);

			if (in_array("telegram_channel", $d) && in_array("telegram_sponsor", $d)) {
				$d = addPoint($task, $_GET["id"]);
			} else {
				$d[] = $_GET["to"];
				$d = array_unique($d);
				file_put_contents(BASEPATH."/storage/task_cache/{$_GET['id']}_std", json_encode([$_GET["to"]]));
			}
		} else {
			file_put_contents(BASEPATH."/storage/task_cache/{$_GET['id']}_std", json_encode([$_GET["to"]]));
		}
		header("Location: {$fp}");
	} else {
		header("Content-Type: text/plain");
		print "Invalid URL redirector {$fp}";
	}

	exit(0);
}

header("Content-Type: text/plain");
http_response_code(403);
print "403 Forbidden";
exit(0);
