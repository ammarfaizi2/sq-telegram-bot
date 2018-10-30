<?php

namespace Sq\Responses;

use Sq\Exe;
use Sq\ResponseFoundation;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @license MIT
 * @version 0.0.1
 * @package \Sq\Responses
 */
class Start extends ResponseFoundation
{
	/**
	 * @return void
	 */
	public function start(): void
	{

$text = "
Welcome to STD market. To claim free 50 VENO Token, please do steps below:

1️⃣ Visit the Website (link website disini) and subscribe our News
2️⃣ Join our Telegram community (link group disini), say some good things in the group
3️⃣ Subscribe our Telegram channel (link channel disini)
4️⃣ After that, fill your information by typing /submit";

		$d = Exe::sendMessage(
			[
				"text" => $text,
				"chat_id" => $this->b->d["message"]["from"]["id"],
				"reply_to_message_id" => $this->b->d["message"]["message_id"]
			]
		);

		var_dump($d["out"]);
	}
}


// [
// 									"text" => "Support \xe2\x98\x8e\xef\xb8\x8f"
// 								],
// 								[
// 									[
// 										"text" => "Referral Link \xf0\x9f\x91\xa5",
// 									],
// 									[
// 										"text" => "Social Media"
// 									]
// 								]