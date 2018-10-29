<?php

namespace Sq\Responses;

use PDO;
use Sq\DB;
use Sq\Exe;
use Sq\ResponseFoundation;

require_once __DIR__."/msg_definer.php";

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @license MIT
 * @version 0.0.1
 * @package \Sq\Responses
 */
class Handler extends ResponseFoundation
{
	/**
	 * @return void
	 */
	public function handle(): void
	{
		if (
			isset(
				$this->b->d["message"]["reply_to_message"]["from"]["is_bot"],
				$this->b->d["message"]["text"]
			) &&
			$this->b->d["message"]["reply_to_message"]["from"]["is_bot"]
		) {

			$text = $this->b->d["message"]["text"];

			if (isset($this->b->d["message"]["reply_to_message"]["text"])) {
				$rdt = $this->b->d["message"]["reply_to_message"]["text"];
			} else if (isset($this->b->d["message"]["reply_to_message"]["caption"])) {
				$rdt = trim($this->b->d["message"]["reply_to_message"]["caption"]);
				if ($rdt === "") {
					return;
				}
			} else {
				return;
			}

			switch ($rdt) {

				case "What is your wallet address?\n\nReply to this message!":
					$pdo = DB::pdo();
					$st = $pdo->prepare("SELECT `wallet` FROM `users` WHERE `id` = :user_id LIMIT 1;");
					$st->execute([":user_id" => $this->b->d["message"]["from"]["id"]]);
					$st = $st->fetch(PDO::FETCH_NUM);

					if (!$st[0]) {
						$rep = "Successfully set a new wallet address!\n\n<b>Your wallet address has been set to:</b> {$text}";
					} else {
						if ($st[0] === $text) {
							$rep = "Wallet address could not be changed because you just sent the same wallet address!\n\nCurrent wallet address which linked to your telegram account is <code>".htmlspecialchars($st[0], ENT_QUOTES, "UTF-8")."</code>";
							$noUpdate = 1;
						} else {
							$rep =
								"Successfully update your wallet address!\n\nYour wallet address <code>".htmlspecialchars($st[0], ENT_QUOTES, "UTF-8")."</code> is now deleted from our database!\n\n<b>Your wallet address has been set to:</b> <code>".htmlspecialchars($st[0], ENT_QUOTES, "UTF-8")."</code> \n\n".
								"Other commands:\n".
								"/info\t\tShow your information\n".
								"/set_wallet\t set/update your wallet address\n".
								"/set_email\t set/update your email address";
						}
					}

					if (!isset($noUpdate)) {
						$st = $pdo->prepare("UPDATE `users` SET `wallet`=:wallet WHERE `id` = :user_id LIMIT 1;");	
						$st->execute([":wallet" => $text, ":user_id" => $this->b->d["message"]["from"]["id"]]);
					}

					unset($st, $pdo);

					Exe::sendMessage(
						[
							"text" => $rep,
							"chat_id" => $this->b->d["message"]["from"]["id"],
							"reply_to_message_id" => $this->b->d["message"]["message_id"],
							"parse_mode" => "HTML"
						]
					);
				break;

				case "To continue, please send the captcha below!\n\nReply to this message!":

					if (!file_exists(BASEPATH."/storage/captcha/{$this->b->d['message']['from']['id']}.txt")) {
						Exe::sendMessage(
							[
								"text" => "Invalid command!\n\nSend /help to see all commands",
								"chat_id" => $this->b->d["message"]["from"]["id"],
								"reply_to_message_id" => $this->b->d["message"]["message_id"],
							]
						);
						return;
					}

					if (file_get_contents(BASEPATH."/storage/captcha/{$this->b->d['message']['from']['id']}.txt") === $text) {
						

						Exe::sendMessage(
							[
								"chat_id" => $this->b->d["message"]["chat"]["id"],
								"text" => "Captcha OK",
								"reply_to_message_id" => $this->b->d["message"]["message_id"],
							]
						);

						unlink(BASEPATH."/storage/captcha/{$this->b->d['message']['from']['id']}.txt");

						Exe::sendMessage(
							[
								"chat_id" => $this->b->d["message"]["chat"]["id"],
								"text" => "What is your email address?\n\nReply to this message!",
								"reply_to_message_id" => $this->b->d["message"]["message_id"],
								"reply_markup" => json_encode(["force_reply" => true])
							]
						);

						$pdo = DB::pdo();

						$st = $pdo->prepare("UPDATE `sessions` SET `state` = '1' WHERE `user_id` = :user_id LIMIT 1;");
						$st->execute([":user_id" => $this->b->d["message"]["from"]["id"]]);

						unset($st, $pdo);

					} else {

						require_once __DIR__."/make_captcha.php";
						file_put_contents(
							BASEPATH."/storage/captcha/{$this->b->d['message']['from']['id']}.txt", 
							makeCaptcha(BASEPATH."/public/captcha_d/{$this->b->d['message']['from']['id']}.png")
						);

						Exe::sendMessage(
							[
								"chat_id" => $this->b->d["message"]["chat"]["id"],
								"text" => "Invalid captcha response",
								"reply_to_message_id" => $this->b->d["message"]["message_id"],
							]
						);

						Exe::sendPhoto(
							[
								"chat_id" => $this->b->d["message"]["from"]["id"],
								"photo" => (
									"https://veno.site/captcha_d/{$this->b->d['message']['from']['id']}.png?std=".time()."&w=".rand()
								),
								"caption" => "To continue, please send the captcha below!\n\nReply to this message!",
								"reply_markup" => json_encode(["force_reply" => true])
							]
						);

					}
				break;


				case "What is your email address?\n\nReply to this message!":
				case "Invalid email address!\n\nPlease reply this message with a valid email address!":
					if (filter_var($text, FILTER_VALIDATE_EMAIL)) {

						$text = strtolower($text);

						$pdo = DB::pdo();
						$st = $pdo->prepare("SELECT `email` FROM `users` WHERE `id` = :user_id LIMIT 1;");
						$st->execute([":user_id" => $this->b->d["message"]["from"]["id"]]);
						$st = $st->fetch(PDO::FETCH_NUM);

						if (!$st[0]) {
							$rep = "Successfully set a new email address!\n\n<b>Your email address has been set to:</b> {$text}";
						} else {
							if ($st[0] === $text) {
								$rep = "Email address could not be changed because you just sent the same email address!\n\nCurrent email address which linked to your telegram account is {$st[0]}";
								$noUpdate = 1;
							} else {
								$rep =
									"Successfully update your email address!\n\nYour email {$st[0]} is now deleted from our database!\n\n<b>Your email address has been set to:</b> {$text}\n\n".
									"Other commands:\n".
									"/info\t\tShow your information\n".
									"/set_wallet\t set/update your wallet address\n".
									"/set_email\t set/update your email address";
							}
						}

						if (!isset($noUpdate)) {
							$st = $pdo->prepare("UPDATE `users` SET `email`=:email WHERE `id` = :user_id LIMIT 1;");	
							$st->execute([":email" => $text, ":user_id" => $this->b->d["message"]["from"]["id"]]);
						}

						unset($st, $pdo);

						Exe::sendMessage(
							[
								"text" => $rep,
								"chat_id" => $this->b->d["message"]["from"]["id"],
								"reply_to_message_id" => $this->b->d["message"]["message_id"],
								"parse_mode" => "HTML"
							]
						);

					} else {
						Exe::sendMessage(
							[
								"text" => __INVALID_EMAIL_ADDRESS,
								"chat_id" => $this->b->d["message"]["from"]["id"],
								"reply_to_message_id" => $this->b->d["message"]["message_id"],
								"parse_mode" => "HTML",
								"reply_markup" => json_encode([
									"force_reply" => true
								])
							]
						);
					}
				break;
			}
		} else {
			Exe::sendMessage(
				[
					"text" => "Invalid command!\n\nSend /help to see all commands",
					"chat_id" => $this->b->d["message"]["from"]["id"],
					"reply_to_message_id" => $this->b->d["message"]["message_id"],
				]
			);
		}
	}
}
