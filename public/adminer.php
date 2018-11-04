<?php
session_start();

if (isset($_SESSION["adminer_d"])) {
	function isolateAdminer() {
		ini_set("display_errors", false);
		@require __DIR__."/../adminer.php";
	}
	isolateAdminer();
	exit;
}

if (isset($_POST["login"], $_POST["username"], $_POST["password"])) {
	if (
		strtolower($_POST["username"]) === "admin" &&
		password_verify($_POST["password"], "\$argon2i\$v=19\$m=1024,t=2,p=2\$eENVUnZla2ZGSHNESmtvLw\$4HEQn7ksvlIMk7+Cp2LD/FX8iYPKVirAyL0TqFW34nA")
	) {
		$_SESSION['adminer_d'] = true;
	}
	header("Location: ?w=".urlencode(rstr(64)));
	exit;
}

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