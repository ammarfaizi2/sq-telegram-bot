<?php

require __DIR__."/../bootstrap/web_init.php";

if (isset($_SESSION["login"]) && $_SESSION["login"] === true) {
	require __DIR__."/home.php";
} else {
	require __DIR__."/login.php";
}
