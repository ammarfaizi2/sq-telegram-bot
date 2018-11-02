<?php

require_once __DIR__."/../bootstrap/web_init.php";

if (!(isset($_SESSION["login"]) && $_SESSION["login"] === true)) {
	header("Location: /login.php?ref=home&w=".urlencode(rstr(64)));
	exit;
}

if (isset($_POST["submit"], $_POST["point"], $_GET["id"], $_GET["save"])) {

	if (!is_numeric($_POST["point"])) {
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<title></title>
			<script type="text/javascript">
				alert('Error: Point must be a number!');
				window.location = '<?php print $_SERVER['HTTP_REFERER']; ?>';
			</script>
		</head>
		<body>
		
		</body>
		</html>
		<?php
		exit;
	}

	if (
		\Sq\DB::pdo()->prepare(
			"UPDATE `tasks` SET `point` = :_point WHERE `id` = :id LIMIT 1;"
		)->execute(
			[
				":_point" => $_POST["point"],
				":id" => $_GET["id"]
			]
		)
	) {
		?><!DOCTYPE html>
		<html>
		<head>
			<title></title>
			<script type="text/javascript">
				alert('Success!');
				window.location = 'edit_point.php?r=<?php print urlencode(rstr(32)); ?>';
			</script>
		</head>
		<body>
		
		</body>
		</html><?php
	}
}

if (isset($_GET["edit"])) {
	$st = \Sq\DB::pdo()->prepare(
		"SELECT `id`,`name`,`point` FROM `tasks` WHERE `id` = :id"
	);
	$st->execute([":id" => $_GET["edit"]]);
	if ($st = $st->fetch(PDO::FETCH_ASSOC)) {
		?><!DOCTYPE html>
		<html>
		<head>
			<title>Edit Point</title>
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
				<a href="edit_point.php?r=<?php print urlencode(rstr(32)); ?>"><button>Back</button></a>
				<form method="post" action="?save=1&id=<?php print $_GET["edit"]; ?>">
					<h1>Edit Point "<?php print htmlspecialchars($st["name"], ENT_QUOTES, "UTF-8"); ?>"</h1>
					Point: <input type="text" name="point" value="<?php print htmlspecialchars($st["point"], ENT_QUOTES, "UTF-8"); ?>"/><br/><br/>
					<input type="submit" name="submit" value="Save"/>
				</form>
			</center>
		</body>
		</html><?php
	} else {
		?><!DOCTYPE html>
		<html>
		<head>
			<title>Invalid Task</title>
		</head>
		<body>
			<h1>Not Found!</h1>
		</body>
		</html><?php
	}
	exit;
}

$st = \Sq\DB::pdo()->prepare("SELECT `id`,`name`,`point` FROM `tasks`;");
$st->execute();

?><!DOCTYPE html>
<html>
<head>
	<title></title>
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
		<h1>CryptoVeno Task Point</h1>
		<table border="1" style="border-collapse: collapse;">
			<tr><th style="padding: 10px;">No.</th><th>Name</th><th>Point</th><th>Action</th></tr>
			<?php  $i = 1; while ($r = $st->fetch(PDO::FETCH_ASSOC)) {
				?>
				<tr>
					<td align="center"><?php print $i++; ?>.</td><td align="center"><?php print htmlspecialchars($r["name"], ENT_QUOTES, "UTF-8"); ?></td>
					<td align="center"><?php print htmlspecialchars($r["point"], ENT_QUOTES, "UTF-8"); ?></td>
					<td align="center"><a href="?edit=<?php print $r["id"]; ?>">Edit</a></td>
				</tr><?php
			} ?>
		</table>
	</center>
</body>
</html>