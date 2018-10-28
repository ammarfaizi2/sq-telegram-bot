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
		$d = Exe::sendMessage(
			[
				"text" => "test",
				"chat_id" => $this->b->d["message"]["from"]["id"]
			]
		);

		var_dump($d);
	}
}
