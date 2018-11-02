<?php

namespace Sq\Responses;

use PDO;
use Sq\DB;
use Sq\Exe;
use CurlFile;
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
					makeCaptcha($imgFile = BASEPATH."/public/captcha_d/{$this->b->d['message']['from']['id']}.png")
				);
				var_dump("sending...\n");
				// $d = Exe::sendPhoto(
				// 	[
				// 		"chat_id" => $this->b->d["message"]["from"]["id"],
				// 		"photo" => (
				// 			"https://veno.site/captcha_d/{$this->b->d['message']['from']['id']}.png?std=".time()."&w=".rand()
				// 		),
				// 		"caption" => "To continue, please send the captcha below!\n\nReply to this message!",
				// 		"reply_markup" => json_encode(["force_reply" => true]),
				// 	]
				// );
				$tkn = trim(file_get_contents(BASEPATH."/config/token.cfg.tmp"));
				$ch = curl_init("https://api.telegram.org/bot{$tkn}/sendPhoto");
				curl_setopt_array($ch, 
					[
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_POST => true,
						CURLOPT_POSTFIELDS => [
							"chat_id" => $this->b->d["message"]["from"]["id"],
							"photo" => (new CurlFile($imgFile)),
							"caption" => "To continue, please send the captcha below!\n\nReply to this message!",
							"reply_markup" => json_encode(["force_reply" => true]),
						],
						CURLOPT_SSL_VERIFYPEER => false,
						CURLOPT_SSL_VERIFYHOST => false
					]
				);
				$out = curl_exec($ch);
				curl_close($ch);

				var_dump($out);
				return;
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

		var_dump($d);
	}

	/**
	 * @return int
	 */
	private function getSubmitSession(): int
	{
		return 0;
	}
}
