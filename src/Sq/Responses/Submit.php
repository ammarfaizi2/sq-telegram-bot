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
class Submit extends ResponseFoundation
{
	/**
	 * @return void
	 */
	public function submit(): void
	{

		switch ($this->getSubmitSession()) {
			case 0:
				$text = "<b>What is your email address?</b>\n\nReply to this message!";
				break;
			
			default:
				break;
		}



		$d = Exe::sendMessage(
			[
				"text" => $text,
				"chat_id" => $this->b->d["message"]["from"]["id"],
				"parse_mode" => "HTML",
				"reply_markup" => [
					"force_reply" => true
				]
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
