<?php

if (!function_exists("rstr")) {
	/**
	 * @param int $n
	 * @param string $e
	 * @return string
	 */
	function rstr(int $n = 32, string $e = null): string
	{
		$r = "";

		if (is_null($e)) {
			$e = "1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOOPASDFGHJKLZXCVBNM___---...";
		}

		$c = strlen($e) - 1;

		for ($i=0; $i < $n; $i++) { 
			$r .= $e[rand(0, $c)];
		}

		return $r;
	}
}