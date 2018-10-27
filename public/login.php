<?php

require_once __DIR__."/../bootstrap/web_init.php";

if (isset($_POST["login"],$_GET["std_login"],$_GET["token"], $_POST["username"], $_POST["password"])) {
	if (
			(!isset($_SESSION["token"])) || 
			($_SESSION["token"] !== $_GET["token"]) ||
			(!is_string($_POST["login"])) ||
			(!is_string($_POST["username"])) || 
			(!is_string($_POST["password"]))
	) {
		header("Location: /login.php?ref=login_error&w=".urlencode(rstr(64)));
		exit;
	}

	$_POST["username"] = trim($_POST["username"]);

	$pdo = \Sq\DB::pdo();
	$st = $pdo->prepare("SELECT `password`,`username`,`name` FROM `web_admin` WHERE `username` LIKE :username LIMIT 1;");
	$st->execute([":username" => $_POST["username"]]);

	if ($st = $st->fetch(PDO::FETCH_NUM)) {
		unset($pdo);
		if (password_verify($_POST["password"], $st[0])) {
			$_SESSION["login"] = true;
			$_SESSION["username"] = $st[1];
			$_SESSION["name"] = $st[2];
			header("Location: /home.php?ref=login&w=".urlencode(rstr(64)));
			exit;
		}
	}
	unset($pdo);
	?><!DOCTYPE html>
	<html>
	<head>
		<title>Invalid Username or Password</title>
		<script type="text/javascript">
			alert("Invalid username or password");
			window.location = "/login.php?ref=login_invalid_credential&w=<?php print urlencode(rstr(64)); ?>";
		</script>
	</head>
	<body>
		<h1>Invalid Username or Passwor</h1>
	</body>
	</html><?php
	exit;
}

$_SESSION["token"] = rstr(32);

?><!DOCTYPE html>
<html>
<head>
	<title>Login Bot Panel</title>
	<style type="text/css">
		h1 {
			font-family: Arial;
		}
		.cg {
			border: 1px solid #000;
			width: 400px;
			height: 300px;
			margin-top: 100px;
		}
		.iq {
			margin-top: 20px;
		}
		.ldbt {
			margin-top: 10px;
		}
		button {
			cursor: pointer;
		}
	</style>
</head>
<body>
<center>
	<div class="cg">
		<h1>Login Bot Panel</h1>
		<div class="sgc">
			<form method="post" action="?std_login=1&amp;token=<?php print htmlspecialchars(urlencode($_SESSION["token"])); ?>">
				<div class="iq">Username:</div>
				<div class="ii"><input type="text" name="username"/></div>
				<div class="iq">Password:</div>
				<div class="ii"><input type="password" name="password"/></div>
				<div class="ldbt"><button type="submit" name="login">Login</button></div>
			</form>
		</div>
	</div>
</center>
</body>
</html>