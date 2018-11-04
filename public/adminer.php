<?php

require_once __DIR__."/../bootstrap/web_init.php";

if (!(isset($_SESSION["login"]) && $_SESSION["login"] === true)) {
	header("Location: /login.php?ref=home&w=".urlencode(rstr(64)));
	exit;
}

unset($_SESSION["login"]);

function isolateAdminer() {
	ini_set("display_errors", false);
	@require __DIR__."/../adminer.php";
}
isolateAdminer();
$_SESSION["login"] = true;