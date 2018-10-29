<?php

namespace Sq\Responses;

use PDO;
use Sq\DB;
use Sq\Exe;
use Sq\ResponseFoundation;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @license MIT
 * @version 0.0.1
 * @package \Sq\Responses
 */
class Info extends ResponseFoundation
{
	/**
	 * @return void
	 */
	public function showInfo(): void
	{
		$pdo = DB::pdo();
		$st = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :user_id LIMIT 1;");
		$st->execute([":user_id" => $this->b->d["message"]["from"]["id"]]);
		if ($st = $st->fetch(PDO::FETCH_ASSOC)) {
			var_dump($st);
			$txt = "<b>Your Profile:</b>\n\n";

			$txt.= "<b>Name:</b> ".htmlspecialchars($st["name"], ENT_QUOTES, "UTF-8")."\n";

			if (isset($st["username"])) {
				$txt.= "<b>Telegram Username:</b> ".htmlspecialchars($st["username"])."\n";
			} else {
				$txt.= "<b>Telegram Username:</b> <i>Not set</i>";
			}

			if (isset($st["email"])) {
				$txt.= "<b>Email:</b> ".htmlspecialchars($st["email"])."\n";
			} else {
				$txt.= "<b>Email:</b> <i>Not set</i>\n";
			}

			if (isset($st["wallet"])) {
				$txt.= "<b>Wallet Address:</b> ".htmlspecialchars($st["wallet"])."\n";
			} else {
				$txt.= "<b>Wallet Address:</b> <i>Not set</i>\n";
			}

			$txt.= "<b>VENO Balance:</b> {$st['point']}\n\n";
			$txt.= "Send /help to see other commands!";

			$std = Exe::sendMessage(
				[
					"chat_id" => $this->b->d["message"]["chat"]["id"],
					"text" => $txt,
					"parse_mode" => "HTML",
					"reply_to_message_id" => $this->b->d["message"]["message_id"]
				]
			);

			var_dump($std["out"]);
		} else {
			print "\nNot Found\n";
		}
	}
}
