<?php

namespace Sq;

use PDO;
use Sq\Responses\Info;
use Sq\Responses\Start;
use Sq\Responses\Submit;
use Sq\Responses\Handler;

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
	 * @var res
	 */
	private $h = NULL;

	/**
	 * @var string
	 */
	private $hdFile;

	/**
	 * Constructor.
	 */
	public function __construct(array $d)
	{
		// Anti DoS
		if (isset($d["message"]["from"]["id"])) {

			$this->hdFile = BASEPATH."/storage/lock_files/{$d["message"]["from"]["id"]}.lock";

			if (file_exists($this->hdFile)) {
				$i = 0;
				while (file_exists($this->hdFile)) {
					$i++;
					sleep(1);

					if ($i === 10) {
						exit(0);
					}
				}
			}

			$this->h = fopen($this->hdFile, "w");
			flock($this->h, LOCK_EX);
			fwrite($this->h, getmypid());
		}
		$this->d = $d;
	}

	/** 
	 * Destructor.
	 */
	public function __destruct()
	{
		if (is_resource($this->h)) {
			fflush($this->h);
			flock($this->h, LOCK_UN);
			fclose($this->h);
			unlink($this->hdFile);
		}
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
				:id, :first_name, :last_name, :username, NULL, NULL, 0, NULL, :started_at
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

		if ("/start" === $text) {
			(new Start($this))->start();
			return;
		}

		if ("/submit" === $text) {
			(new Submit($this))->submit();
			return;
		}

		var_dump($text." std::hd");
		if ("/info" === $text) {
			var_dump($text);
			(new Info($this))->showInfo();
			return;
		}

		if ("/help" === $text) {
			Exe::sendMessage(
				[
					"chat_id" => $this->d["message"]["chat"]["id"],
					"text" => (
						"/info\t\tShow your information\n".
						"/set_wallet\t set/update your wallet address\n".
						"/set_email\t set/update your email address"
					),
					"reply_to_message_id" => $this->d["message"]["message_id"]
				]
			);
			return;
		}


		(new Handler($this))->handle();
	}

	/**
	 * @return void
	 */
	public function run(): void
	{
		$this->responseRoutes();
	}
}
