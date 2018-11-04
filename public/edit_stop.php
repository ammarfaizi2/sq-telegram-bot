<?php

require_once __DIR__."/../bootstrap/web_init.php";

if (!(isset($_SESSION["login"]) && $_SESSION["login"] === true)) {
	header("Location: /login.php?ref=home&w=".urlencode(rstr(64)));
	exit;
}

$stop = (int)file_get_contents(BASEPATH."/storage/stop.txt");

if (isset($_GET["max_amount"], $_GET["save"], $_POST["amount"])) {
	if (!is_numeric($_POST["amount"])) {
		$alert = "Amount must be a number and greater than 0";
		$red = $_SERVER["HTTP_REFERER"];
	}

	if (!isset($alert)) {
		$_POST["amount"] = (int)$_POST["amount"];
		if ($_POST["amount"] > 0) {
			$alert = "Success!";
			$red = "/index.php?w=".urlencode(rstr(64));
			file_put_contents(BASEPATH."/storage/stop.txt", $_POST["amount"]);
		} else {
			$alert = "Amount must be greater than 0";
			$red = $_SERVER["HTTP_REFERER"];
		}
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
}

?><!DOCTYPE html>
<html>
<head>
	<title></title>
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
		<form method="post" action="?max_amount=1&save=1">
			<div style="margin-top: 30px;">
				<h3>Max Members Amount</h3>
				<input type="text" name="amount" value="<?php print $stop; ?>"/><br/><br/>
				<input type="submit" name="submit" value="Save">
			</div>
		</form>
	</div>
	</center>
</body>
</html>