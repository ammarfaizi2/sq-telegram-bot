<?php

require_once __DIR__."/../bootstrap/web_init.php";

if (!(isset($_SESSION["login"]) && $_SESSION["login"] === true)) {
	header("Location: /login.php?ref=home&w=".urlencode(rstr(64)));
	exit;
}

$st = \Sq\DB::pdo()->prepare(
	"SELECT * FROM `users` ORDER BY `started_at` ASC LIMIT 25 OFFSET 0;"
);
$st->execute();
?>
<!DOCTYPE html>
<html>
<head>
	<title>CRYPTOVENO Members</title>
	<style type="text/css">
		* {
			font-family: Arial;
		}
		th {
			padding-left: 30px;
			padding-right: 30px;
		}
		td {
			padding-left: 3px;
			padding-right: 3px;
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
		<h1>CryptoVeno Members</h1>
		<table border="1" style="border-collapse: collapse;">
			<tr><th align="center" style="padding: 5px;">No.</th><th align="center">Name</th><th align="center">Username</th><th align="center">Email</th><th align="center">Wallet</th><th align="center">Balance</th><th>Twitter</th><th>Facebook</th><th>Medium</th><th align="center">Joined At</th><th align="center">Started At</th></tr>
			<?php $i = 1; while ($r = $st->fetch(PDO::FETCH_ASSOC)) { ?>
				<tr>
					<td align="center"><?php print $i++; ?>.</td>
					<td align="center"><?php print htmlspecialchars($r["name"], ENT_QUOTES, "UTF-8"); ?></td>
					<td align="center"><a target="_blank" href="https://t.me/<?php print substr($r["username"], 1); ?>"><?php print htmlspecialchars($r["username"], ENT_QUOTES, "UTF-8"); ?></a></td>
					<td align="center"><?php print htmlspecialchars($r["email"], ENT_QUOTES, "UTF-8"); ?></td>
					<td align="center"><?php print htmlspecialchars($r["wallet"], ENT_QUOTES, "UTF-8"); ?></td>
					<td align="center"><?php print htmlspecialchars($r["balance"], ENT_QUOTES, "UTF-8"); ?></td>
					<td align="center"><a target="_blank" href="<?php print htmlspecialchars($r["twitter_link"], ENT_QUOTES, "UTF-8") ?>"><?php print htmlspecialchars($r["twitter_link"], ENT_QUOTES, "UTF-8"); ?></a></td>
					<td align="center"><a target="_blank" href="<?php print htmlspecialchars($r["medium_link"], ENT_QUOTES, "UTF-8"); ?>"><?php print htmlspecialchars($r["facebook_link"], ENT_QUOTES, "UTF-8"); ?></a></td>
					<td align="center"><a target="_blank" href="<?php print htmlspecialchars($r["medium_link"], ENT_QUOTES, "UTF-8"); ?>"><?php print htmlspecialchars($r["medium_link"], ENT_QUOTES, "UTF-8"); ?></a></td>
					<td align="center"><?php print htmlspecialchars($r["joined_at"], ENT_QUOTES, "UTF-8"); ?></td>
					<td align="center"><?php print htmlspecialchars($r["started_at"], ENT_QUOTES, "UTF-8"); ?></td>
				</tr>
			<?php } ?>	
		</table>
	</center>
</body>
</html>