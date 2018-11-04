<?php

require __DIR__."/../bootstrap/web_init.php";

if (!(isset($_SESSION["login"]) && $_SESSION["login"] === true)) {
	header("Location: /login.php?ref=home&w=".urlencode(rstr(64)));
	exit;
}

$file = BASEPATH."/config/token.cfg.tmp";

if (isset($_GET["add_token_action"], $_POST["tokend"])) {

	$me = BASEPATH."/config/me.cfg.tmp";

	$_POST["tokend"] = trim($_POST["tokend"]);

	$rd = curld("https://api.telegram.org/bot{$_POST['tokend']}/getMe");
	if ($rd["errno"]) {
		print "Error: {$rd['error']}\n";
		exit(1);
	}
	$rd = json_decode($rd["out"], true);
	if (!is_array($rd)) {
		print "Invalid response: {$rd['out']}\n";
	}

	if (
		isset(
			$rd["ok"],
			$rd["result"]["id"],
			$rd["result"]["is_bot"],
			$rd["result"]["first_name"],
			$rd["result"]["username"]
		) &&
		$rd["ok"]
	) {
		if (
			file_put_contents($file, $_POST["tokend"]) &&
			file_put_contents($me, json_encode(
				$rd, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
			))
		) {
			?><!DOCTYPE html>
			<html>
			<head>
				<title>Success</title>
				<script type="text/javascript">
					alert("Success!");
					window.location = "home.php?ref=add_token_action&w=<?php print(urlencode(rstr(64))); ?>";
				</script>
			</head>
			<body>
				<center>
					<h1>Success!</h1>
				</center>
			</body>
			</html><?php
		}
	}
	exit;
}


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


if (isset($_GET["add_token"])):?>
<!DOCTYPE html>
<html>
<head>
	<title>Tambahkan Token Bot</title>
	<style type="text/css">
		* {
			font-family: Arial;
		}
		.mcgd {
			margin-top: 150px;
		}
		button {
			cursor: pointer;
		}
		.fr {
			margin-top: 80px;
		}
		.qw {
			margin-top: 20px;
		}
		.er {
			margin-top: 10px;
		}
	</style>
</head>
<body>
	<center>
		<a href="index.php?w=<?php print urlencode(rstr(64)); ?>"><button>Back</button></a>
		<form action="?add_token_action=1" method="post">
			<div class="fr">
				<h1>Masukkan token bot</h1>
			</div>
			<div>
				<div class="qw">
					<input type="text" name="tokend" required/><br/>
				</div>
				<div class="er">
					<button>Save</button>
				</div>
			</div>
		</form>
	</center>
</body>
</html>
<?php exit;
endif;



?><!DOCTYPE html>
<html>
<head>
	<title>Ubah Token Bot</title>
	<style type="text/css">
		* {
			font-family: Arial;
		}
		.mcgd {
			margin-top: 10px;
		}
		button {
			cursor: pointer;
		}
	</style>
</head>
<body>
	<center>
		<a href="index.php?w=<?php print urlencode(rstr(64)); ?>"><button>Back</button></a>
		<div class="mcgd">
			<?php
				if ($status === "empty_token") {
					?>
						<h1>Token Bot Kosong</h1>
						<a href="?add_token=1"><button>Tambahkan Token</button></a>
					<?php
				} else if ($status === "token_exists") {
					?>
						<h2>Token Bot Saat Ini:</h2>
						<h3><?php print $token; ?></h3>
						<a href="?add_token=1"><button>Ubah Token</button></a>
					<?php
				}
			?>
		</div>
	</center>
</body>
</html>