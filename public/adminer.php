<?php

require_once __DIR__."/../bootstrap/web_init.php";

function isolateAdminer() {
	ini_set("display_errors", false);
	@require __DIR__."/../adminer.php";
}
isolateAdminer();