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
		var_dump(1);

		if (
			isset(
				$this->b->d["message"]["reply_to_message"]["text"],
				$this->b->d["message"]["reply_to_message"]["from"]["is_bot"],
				$this->b->d["message"]["text"]
			) &&
			$this->b->d["message"]["reply_to_message"]["from"]["is_bot"]
		) {
			var_dump(2);

			$text = $this->b->d["message"]["text"];
			var_dump(
				$this->b->d["message"]["reply_to_message"]["text"],
				"What is your email address?\n\nReply to this message!",
				$this->b->d["message"]["reply_to_message"]["text"] === "What is your email address?\n\nReply to this message!"
			);

			switch ($this->b->d["message"]["reply_to_message"]["text"]) {
				case "What is your email address?\n\nReply to this message!":
				case "Invalid email address!\n\nPlease reply this message with a valid email address!":
				var_dump(3);
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
								$rep = "Successfully update your email address!\n\nYour email {$st[0]} is now deleted from our database!\n\n<b>Your email address has been set to:</b> {$text}";
							}
						}

						if (!isset($noUpdate)) {
							$st = $pdo->prepare("UPDATE `users` SET `email`=:email WHERE `id` = :user_id LIMIT 1;");	
							$st->execute([":email" => $text, ":user_id" => $this->b->d["message"]["from"]["id"]]);
						}
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
								"reply_markup" => [
									"force_reply" => true
								]
							]
						);
					}
				break;
			}
		}
	}
}
