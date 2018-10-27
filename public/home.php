<?php

require_once __DIR__."/../bootstrap/web_init.php";

if (!(isset($_SESSION["login"]) && $_SESSION["login"] === true)) {
	header("Location: /login.php?ref=home&w=".urlencode(rstr(64)));
	exit;
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
				<td>
					<a href="change_bot_token.php?ref=home&w=<?php print urlencode(rstr(64)); ?>">
						<button>Ubah Token Bot</button>
					</a>
				</td>
			</tr>
		</table>
	</center>
</body>
</html>