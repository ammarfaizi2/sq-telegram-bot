<?php

if (!function_exists("addPoint")) {
	/**
	 * @param int $taskId
	 * @param int $userId
	 * @return bool
	 */
	function addPoint(int $taskId, int $userId): bool
	{
		$pdo = \Sq\DB::pdo();
		$st = $pdo->prepare("SELECT COUNT(1) FROM `users_task` WHERE `user_id` = :user_id AND `task_id` = :task_id LIMIT 1;");
		$st->execute([":user_id" => $userId, ":task_id" => $taskId]);
		$st = $st->fetch(PDO::FETCH_NUM);
		if ($st[0] == 0) {

			$st = $pdo->prepare("SELECT `point` FROM `tasks` WHERE `id` = :task_id LIMIT 1;");
			$st->execute([":task_id" => $taskId]);
			if ($st = $st->fetch(PDO::FETCH_NUM)) {
				$st = $pdo->prepare("UPDATE `users` SET `balance` = `balance` + :_point WHERE `id` = :user_id LIMIT 1;");
				$exe = $st->execute(
					[
						":_point" => $point,
						":user_id" => $userId,
					]
				);
				return $exe && $pdo->prepare(
					"INSERT INTO `users_task` (`user_id`,`task_id`,`taskhash`,`point`,`created_at`) VALUES (:user_id, :task_id, :taskhash, :_point, :created_at);"
				)->execute(
					[
						":user_id" => $userId,
						":task_id" => $taskId,
						":taskhash" => $userId."|".$taskId,
						":_point" => $st[0],
						":created_at" => date("Y-m-d H:i:s")
					]
				);
			} else {
				print "Error occured when adding point!";
				exit(1);
			}
		}
		unset($pdo);
		return false;
	}
}

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