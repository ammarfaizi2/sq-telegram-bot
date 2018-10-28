<?php

namespace Sq;

defined("BOT_TOKEN") or define("BOT_TOKEN", trim(file_get_contents(BASEPATH."/config/token.cfg.tmp")));

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @license MIT
 * @version 0.0.1
 * @package \Sq
 */
final class Exe
{
	/**
	 * @param string $method
	 * @param array  $parameters
	 * @return array
	 */
	public function __call($method, $parameters)
	{
		return self::{$method}(...$parameters);
	}

	/**
	 * @param string $method
	 * @param array  $parameters
	 * @return array
	 */
	public static function __callStatic($method, $parameters)
	{
		return self::exec(
			$method, 
			(isset($parameters[0]) ? $parameters[0] : null),
			(isset($parameters[1]) ? $parameters[1] : "POST")
		);
	}

	/**
	 * @param string      $method
	 * @param array|null  $postData
	 * @param string	  $method
	 */
	private static function exec(string $method, $postData, $httpMethod)
	{
		if ($httpMethod === "GET") {
			$ch = curl_init(
				"https://api.telegram.org/bot".BOT_TOKEN."/".$method."?".http_build_query($postData)
			);
			curl_setopt_array($ch, 
				[
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_SSL_VERIFYHOST => false,
					CURLOPT_SSL_VERIFYPEER => false
				]
			);
		} else {
			$ch = curl_init(
				"https://api.telegram.org/bot".BOT_TOKEN."/".$method
			);
			curl_setopt_array($ch, 
				[
					CURLOPT_POST => true,
					CURLOPT_POSTFIELDS => http_build_query($postData),
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_SSL_VERIFYHOST => false,
					CURLOPT_SSL_VERIFYPEER => false
				]
			);
		}

		$out = curl_exec($ch);
		$info = curl_getinfo($ch);
		$errno = curl_errno($ch);
		$error = curl_error($ch);
		curl_close($ch);

		return [
			"out" => $out,
			"info" => $info,
			"errno" => $errno,
			"error" => $error
		];
	}
}
