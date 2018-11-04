<?php

require_once __DIR__."/../bootstrap/web_init.php";

if (!(isset($_SESSION["login"]) && $_SESSION["login"] === true)) {
	header("Location: /login.php?ref=home&w=".urlencode(rstr(64)));
	exit;
}

$stopMsg = file_get_contents(BASEPATH."/storage/stop_message.txt");

if (isset($_POST["submit"], $_GET["save"], $_POST["data"])) {
	file_put_contents(BASEPATH."/storage/stop_message.txt", $_POST["data"]);
		?><!DOCTYPE html>
<html>
<head>
	<title></title>
	<script type="text/javascript">
		alert("Success!");
		window.location = "/index.php?w=<?php print urlencode(rstr(64)); ?>";
	</script>
</head>
<body>

</body>
</html><?php exit;
}

?><!DOCTYPE html>
<html>
<head>
	<title>Edit Stop Message</title>
	<style type="text/css">
		* {
			font-family: Arial;
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
	<div class="cgd">
		<a href="/index.php?w=<?php print urlencode(rstr(64)); ?>"><button>Back</button></a>
		<form method="post" action="?save=1">
			<div style="margin-top: 30px;">
				<h3>Stop Message</h3>
				<textarea name="data" required style="width: 332px; height: 163px;"><?php print htmlspecialchars($stopMsg, ENT_QUOTES, "UTF-8"); ?></textarea><br/><br/>
				<input type="submit" name="submit" value="Save">
			</div>
		</form>
	</div>
	</center>
</body>
</html>