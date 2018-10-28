<?php

namespace Sq\Responses;

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

		switch ($this->getSubmitSession()) {
			case 0:
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
