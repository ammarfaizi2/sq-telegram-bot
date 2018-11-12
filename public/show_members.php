<?php

require_once __DIR__."/../bootstrap/web_init.php";

if (!(isset($_SESSION["login"]) && $_SESSION["login"] === true)) {
	header("Location: /login.php?ref=home&w=".urlencode(rstr(64)));
	exit;
}

$offset = 0;
$limit = 25;
if (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 1) {
	$offset = $limit*($_GET["page"]-1);
}

$pdo = \Sq\DB::pdo();

$st = $pdo->prepare("SELECT COUNT(1) FROM `users`;");
$st->execute();
$st = $st->fetch(PDO::FETCH_NUM);
$totalPage = (int)ceil($st[0] / $limit);
$st = $pdo->prepare("SELECT * FROM `users` ORDER BY `started_at` ASC LIMIT {$limit} OFFSET {$offset};");
$st->execute();

ob_start();
?><div style="padding: 10px; padding: 10px;width:100%;word-wrap:break-word;">
<?php for ($i=1; $i <= $totalPage; $i++) { ?><a href="?page=<?php print $i; ?>" class="dd"><?php print $i; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?></div><?php
$pg = ob_get_clean();



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
		.dd {
			font-size: 32px;
			text-decoration: none;
		}
		.dd:hover {
			text-decoration: underline;
		}
	</style>
</head>
<body>
	<center>
		<a href="/index.php"><button>Back to Home</button></a>
		<h1>CryptoVeno Members</h1>
		<div style="margin-bottom: 10px;">
			<button id="export">Export Spreadsheet</button>
			<div>
				<h2 id="gen" style="display: none;">Generating Spreadsheet File...</h2>
			</div>
		</div>
		<?php print $pg; ?>
		<table border="1" style="border-collapse: collapse;">
			<tr><th align="center" style="padding: 5px;">No.</th><th align="center">Name</th><th align="center">Username</th><th align="center">Email</th><th align="center">Wallet</th><th align="center">Balance</th><th>Twitter</th><th>Facebook</th><th>Medium</th><th align="center">Joined At</th><th align="center">Started At</th></tr>
			<?php $i = $offset + 1; while ($r = $st->fetch(PDO::FETCH_ASSOC)) { ?>
				<tr>
					<td align="center"><?php print $i++; ?>.</td>
					<td align="center"><?php print htmlspecialchars($r["name"], ENT_QUOTES, "UTF-8"); ?></td>
					<td align="center"><a target="_blank" href="https://t.me/<?php print substr($r["username"], 1); ?>"><?php print htmlspecialchars($r["username"], ENT_QUOTES, "UTF-8"); ?></a></td>
					<td align="center"><?php print htmlspecialchars($r["email"], ENT_QUOTES, "UTF-8"); ?></td>
					<td align="center"><?php print htmlspecialchars($r["wallet"], ENT_QUOTES, "UTF-8"); ?></td>
					<td align="center"><?php print htmlspecialchars($r["balance"], ENT_QUOTES, "UTF-8"); ?></td>
					<td align="center"><a target="_blank" href="<?php print htmlspecialchars($r["twitter_link"], ENT_QUOTES, "UTF-8") ?>"><?php print htmlspecialchars($r["twitter_link"], ENT_QUOTES, "UTF-8"); ?></a></td>
					<td align="center"><a target="_blank" href="<?php print htmlspecialchars($r["facebook_link"], ENT_QUOTES, "UTF-8"); ?>"><?php print htmlspecialchars($r["facebook_link"], ENT_QUOTES, "UTF-8"); ?></a></td>
					<td align="center"><a target="_blank" href="<?php print htmlspecialchars($r["medium_link"], ENT_QUOTES, "UTF-8"); ?>"><?php print htmlspecialchars($r["medium_link"], ENT_QUOTES, "UTF-8"); ?></a></td>
					<td align="center"><?php print htmlspecialchars($r["joined_at"], ENT_QUOTES, "UTF-8"); ?></td>
					<td align="center"><?php print htmlspecialchars($r["started_at"], ENT_QUOTES, "UTF-8"); ?></td>
				</tr>
			<?php } ?>
		</table>
		<?php print $pg; ?>
		<script type="text/javascript">
			var gen = document.getElementById("gen");
			function generate() {
				var ch = new XMLHttpRequest;
				ch.onreadystatechange = function () {
					if (this.readyState === 4) {
						var json = JSON.parse(this.responseText);
						var file = json["f"];
						const interval = setInterval(function () {
							checker(file, interval);
						}, 1000);
					}
				};
				ch.withCredentials = true;
				ch.open("GET", "export.php?action=1");
				ch.send();
			}
			function checker(file, interval) {
				var ch = new XMLHttpRequest;
				ch.onreadystatechange = function () {
					if (this.readyState === 4) {
						var json = JSON.parse(this.responseText);
						if (json["f"]) {
							file = file.split("/");
							file = file[file.length - 1];
							clearInterval(interval);
							var fileurl = "http://"+window.location.hostname+"/xlsx/"+file;
							gen.innerHTML = "Download file: <a target=\"_blank\" href=\""+fileurl+"\">"+fileurl+"</a>";
						}
					}
				};
				ch.withCredentials = true;
				ch.open("GET", "export.php?check="+encodeURIComponent(file));
				ch.send();
			}
			document.getElementById("export").addEventListener("click", function () {
				gen.style.display = "block";
				generate();	
			});
		</script>
	</center>
</body>
</html>