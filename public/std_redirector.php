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
		$d = addPoint($task, $_GET["id"]);
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
