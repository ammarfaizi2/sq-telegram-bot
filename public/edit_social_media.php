<?php

require_once __DIR__."/../bootstrap/web_init.php";

if (!(isset($_SESSION["login"]) && $_SESSION["login"] === true)) {
	header("Location: /login.php?ref=home&w=".urlencode(rstr(64)));
	exit;
}

$telegramGroup = htmlspecialchars(file_get_contents(BASEPATH."/storage/redirector/telegram_group.txt"), ENT_QUOTES, "UTF-8"); 
$telegramChannel = htmlspecialchars(file_get_contents(BASEPATH."/storage/redirector/telegram_channel.txt"), ENT_QUOTES, "UTF-8"); 
$twitterUrl = htmlspecialchars(file_get_contents(BASEPATH."/storage/redirector/twitter.txt"), ENT_QUOTES, "UTF-8");
$facebookUrl = htmlspecialchars(file_get_contents(BASEPATH."/storage/redirector/facebook.txt"), ENT_QUOTES, "UTF-8");
$mediumUrl = htmlspecialchars(file_get_contents(BASEPATH."/storage/redirector/medium.txt"), ENT_QUOTES, "UTF-8");

if (
isset($_POST["submit"], $_POST["data"], $_GET["save"], $_GET["hd"]) &&
in_array(
	$_GET["hd"], ["telegram_group", "telegram_channel", "facebook", "twitter", "medium"]
)

) {
	$v = 
	(
		($_GET["hd"] === "telegram_group") ? "telegram_group.txt" :
			(
				($_GET["hd"] === "telegram_channel") ? "telegram_channel.txt" :
					(
						($_GET["hd"] === "twitter") ? "twitter.txt" :
							(
								($_GET["hd"] === "facebook") ? "facebook.txt":
									(
										($_GET["hd"] === "medium") ? "medium.txt" : null
									)
							)
					)
			)
	);

	if (!is_null($v)) {
		$_POST["data"] = trim($_POST["data"]);
		if (filter_var($_POST["data"], FILTER_VALIDATE_URL)) {
			file_put_contents(BASEPATH."/storage/redirector/{$v}", $_POST["data"]);
			$alert = "Success!";
			$red = "?w=".urlencode(rstr(64));
		} else {
			$_POST["data"] = stripslashes($_POST["data"]);
			$alert = "Invalid URL {$_POST["data"]}";
			$red = $_SERVER["HTTP_REFERER"];
		}

		?><!DOCTYPE html>
		<html>
		<head>
			<title></title>
			<script type="text/javascript">
				alert("<?php print $alert; ?>");
				window.location = "<?php print $red; ?>";
			</script>
		</head>
		<body>
		</body>
		</html><?php
		exit;
	} else {
		print "Error!";
	}
	exit;
}

if (isset($_GET["edit"]) && in_array(
	$_GET["edit"], ["telegram_group", "telegram_channel", "twitter", "facebook", "medium"]
)) {

	$v = 
	(
		($_GET["edit"] === "telegram_group") ? $telegramGroup :
			(
				($_GET["edit"] === "telegram_channel") ? $telegramChannel :
					(
						($_GET["edit"] === "twitter") ? $twitterUrl :
							(
								($_GET["edit"] === "facebook") ? $facebookUrl:
									(
										($_GET["edit"] === "medium") ? $mediumUrl : null
									)
							)
					)
			)
	);

	?><!DOCTYPE html>
	<html>
	<head>
		<title>Edit <?php print $_GET["edit"]; ?></title>
		<style type="text/css">
			* {
				font-family: Arial;
			}
			th {
				padding-left: 30px;
				padding-right: 30px;
			}
			td {
				padding-left: 10px;
				padding-right: 10px;
			}
			a {
				color:blue;
			}
			button {
				cursor: pointer;
			}
			input[type=submit] {
				cursor: pointer;
			}
		</style>
	</head>
	<body>
		<center>
			<a href="?w=<?php print urlencode(rstr(32)); ?>"><button>Back</button></a>
			<h1>Edit <?php print $_GET["edit"]; ?></h1>
			<form method="POST" action="?save=1&amp;hd=<?php print urlencode($_GET["edit"]); ?>">
				URL: <input type="text" name="data" value="<?php print $v; ?>"/><br/><br/>
				<input type="submit" name="submit" value="Save"/>
			</form>
		</center>
	</body>
	</html><?php
	exit;
}


?><!DOCTYPE html>
<html>
<head>
	<title>Edit Social Media</title>
	<style type="text/css">
		* {
			font-family: Arial;
		}
		th {
			padding-left: 30px;
			padding-right: 30px;
		}
		td {
			padding-left: 10px;
			padding-right: 10px;
		}
		a {
			color:blue;
		}
		button {
			cursor: pointer;
		}
	</style>
</head>
<body>
	<center>
		<a href="/index.php"><button>Back to Home</button></a>
		<h1>Edit Social Media</h1>
		<table border="1" style="border-collapse: collapse;">
			<tr><th style="padding: 10px;">No.</th><th>Name</th><th>Link</th><th>Action</th></tr>
			<tr><td align="center">1.</td><td align="center">Telegram Group</td><td align="center"><?php print $telegramGroup; ?></td><td align="center"><a href="?edit=telegram_group">Edit</a></td></tr>
			<tr><td align="center">2.</td><td align="center">Telegram Channel</td><td align="center"><?php print $telegramChannel; ?></td><td align="center"><a href="?edit=telegram_channel">Edit</a></td></tr>
			<tr><td align="center">3.</td><td align="center">Twitter</td><td align="center"><?php print $twitterUrl; ?></td><td align="center"><a href="?edit=twitter">Edit</a></td></tr>
			<tr><td align="center">4.</td><td align="center">Facebook Fanspage</td><td align="center"><?php print $facebookUrl; ?></td><td align="center"><a href="?edit=facebook">Edit</a></td></tr>
			<tr><td align="center">5.</td><td align="center">Medium</td><td align="center"><?php print $mediumUrl; ?></td><td align="center"><a href="?edit=medium">Edit</a></td></tr>
		</table>
	</center>
</body>
</html>