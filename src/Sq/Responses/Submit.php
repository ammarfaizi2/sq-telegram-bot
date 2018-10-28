<?php

namespace Sq\Responses;

use Sq\DB;
use Sq\PDO;
use Sq\Exe;
use Sq\ResponseFoundation;

require_once __DIR__."/msg_definer.php";

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @license MIT
 * @version 0.0.1
 * @package \Sq\Responses
 */
class Submit extends ResponseFoundation
{
	/**
	 * @return void
	 */
	public function submit(): void
	{

		$pdo = DB::pdo();
		$st = $pdo->prepare("SELECT `state` FROM `sessions` WHERE `user_id` = :user_id LIMIT 1;");
		$st->execute([":user_id" => $this->b->d["message"]["from"]["id"]]);

		if ($st = $st->fetch(PDO::FETCH_NUM)) {
			$state = $st[0];
		} else {
			$state = 0;
			$st = $pdo->prepare("INSERT INTO `sessions` (`user_id`, `state`) VALUES (:user_id, :state);");
			$st->execute([":user_id" => $this->b->d["message"]["from"]["id"], ":state" => 1]);
		}

		switch ($this->getSubmitSession()) {
			case 0:
				require_once __DIR__."/make_captcha.php";
				file_put_contents(
					BASEPATH."/storage/captcha/{$this->b->d['message']['from']['id']}.txt", 
					makeCaptcha(BASEPATH."/public/captcha_d/{$this->b->d['message']['from']['id']}.png")
				);
				Exe::sendPhoto(
					[
						"chat_id" => $this->b->d["message"]["from"]["id"],
						"photo" => "https://veno.site/captcha_d/{$this->b->d['message']['from']['id']}.png",
						"caption" => "To continue, please send the captcha below!",
						"reply_markup" => json_encode(["force_reply" => true]),
					]
				);
				return;
				break;
			case 1:
				$text = __ASK_EMAIL;
				break;
			default:
				break;
		}

		$d = Exe::sendMessage(
			[
				"text" => $text,
				"chat_id" => $this->b->d["message"]["from"]["id"],
				"parse_mode" => "HTML",
				"reply_markup" => json_encode(["force_reply" => true])
			]
		);
	}

	/**
	 * @return int
	 */
	private function getSubmitSession(): int
	{
		return 0;
	}
}
