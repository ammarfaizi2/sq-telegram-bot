<?php

if (!defined("SQ_INIT__")):

	define("SQ_INIT__", 1);

	require __DIR__."/../config/init.php";

	/**
	 * @param string $class
	 * @return void
	 */
	function sqInternalClassAutoloader(string $class): void
	{
		$class = str_replace("\\", "/", $class);
		if (file_exists($class = BASEPATH."/src/{$class}.php")) {
			require $class;
		}
	}

	spl_autoload_register("sqInternalClassAutoloader");

	require BASEPATH."/vendor/autoload.php";
	
	require BASEPATH."/src/helpers.php";
	require BASEPATH."/config/database.php";
endif;
