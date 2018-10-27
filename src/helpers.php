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

if (!function_exists("curld")) {
	/**
	 * @param string $url
	 * @param array  $opt
	 * @return array
	 */
	function curld(string $url, array $opt = []): array
	{
		$ch = curl_init($url);
		$optf = [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false
		];
		foreach ($opt as $k => $v) {
			$optf[$k] = $v;
		}
		curl_setopt_array($ch, $optf);
		$out = curl_exec($ch);
		$info = curl_getinfo($ch);
		$err = curl_error($ch);
		$ern = curl_errno($ch);
		curl_close($ch);
		return [
			"out" => $out,
			"info" => $info,
			"error" => $err,
			"errno" => $ern
		];
	}
}