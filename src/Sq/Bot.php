<?php

namespace Sq;

use PDO;
use Sq\Responses\Start;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @license MIT
 * @version 0.0.1
 * @package \Sq
 */
final class Bot
{
	/**
	 * @var array
	 */
	public $d;

	/**
	 * Constructor.
	 */
	public function __construct(array $d)
	{
		$this->d = $d;
	}

	/**
	 * @return void
	 */
	public function responseRoutes(): void
	{
		if (!(
			isset($this->d["message"]["chat"]["type"]) &&
			$this->d["message"]["chat"]["type"] === "private"
		)) {
			return;
		}

		$pdo = DB::pdo();
		$st = $pdo->prepare("SELECT `id` FROM `users` WHERE `id` = :id LIMIT 1;");
		$st->execute([":id" => $this->d["message"]["from"]["id"]]);
		if (!$st->fetch(PDO::FETCH_NUM)) {
			$st = $pdo->prepare("INSERT INTO `users` VALUES (
				:id, :first_name, :last_name, :username, NULL, NULL, NULL, :started_at
			);");
			$st->execute(
				[
					":id" => $this->d["message"]["from"]["id"],
					":first_name" => $this->d["message"]["from"]["first_name"],
					":last_name" => (
						isset($this->d["message"]["from"]["last_name"]) ?
							$this->d["message"]["from"]["last_name"] :
								NULL
					),
					":username" => (
						isset($this->d["message"]["from"]["username"]) ? 
							"@".$this->d["message"]["from"]["username"] :
								NULL
					),
					":started_at" => date("Y-m-d H:i:s")
				]
			);
		}
		unset($st, $pdo);

		$text = isset($this->d["message"]["text"]) ? $this->d["message"]["text"] : null;

		if (preg_match("/^\/start$/Usi", $text)) {
			(new Start($this))->start();
			return;
		}

		if (preg_match("/^\/submit$/Usi", $text)) {
			(new Submit($this))->submit();
			return;
		}
	}

	/**
	 * @return void
	 */
	public function run(): void
	{
		$this->responseRoutes();
	}
}
