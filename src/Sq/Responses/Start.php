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
class Start extends ResponseFoundation
{
	/**
	 * @return void
	 */
	public function start(): void
	{

		// $telegramGroup = htmlspecialchars(file_get_contents(BASEPATH."/storage/redirector/telegram_group.txt"), ENT_QUOTES, "UTF-8"); 
		// $telegramChannel = htmlspecialchars(file_get_contents(BASEPATH."/storage/redirector/telegram_channel.txt"), ENT_QUOTES, "UTF-8"); 
		// $twitterUrl = htmlspecialchars(file_get_contents(BASEPATH."/storage/redirector/twitter.txt"), ENT_QUOTES, "UTF-8");
		// $facebookUrl = htmlspecialchars(file_get_contents(BASEPATH."/storage/redirector/facebook.txt"), ENT_QUOTES, "UTF-8");
		// $mediumUrl = htmlspecialchars(file_get_contents(BASEPATH."/storage/redirector/medium.txt"), ENT_QUOTES, "UTF-8");

		$telegramGroup = /*htmlspecialchars*/(file_get_contents(BASEPATH."/storage/redirector/telegram_group.txt")/*, ENT_QUOTES, "UTF-8"*/); 
		$telegramChannel = /*htmlspecialchars*/(file_get_contents(BASEPATH."/storage/redirector/telegram_channel.txt")/*, ENT_QUOTES, "UTF-8"*/); 
		$twitterUrl = /*htmlspecialchars*/(file_get_contents(BASEPATH."/storage/redirector/twitter.txt")/*, ENT_QUOTES, "UTF-8"*/);
		$facebookUrl = /*htmlspecialchars*/(file_get_contents(BASEPATH."/storage/redirector/facebook.txt")/*, ENT_QUOTES, "UTF-8"*/);
		$mediumUrl = /*htmlspecialchars*/(file_get_contents(BASEPATH."/storage/redirector/medium.txt")/*, ENT_QUOTES, "UTF-8"*/);


		$tasks = [
			[
				[
					"text" => "Join Telegram Group",
					"url" => $telegramGroup
				]
			],
			[
				[
					// "text" => "Join Telegram Channel",
					// "url" => "https://bot.cryptoveno.com/std_redirector.php?to=telegram_channel&id={$this->b->d["message"]["from"]["id"]}",
					"text" => "Join Our Channel & Sponsor",
					"callback_data" => "jnd"
				]
			],
			[
				[
					"text" => "Follow & Retweet Our Twitter",
					// "url" => "https://bot.cryptoveno.com/std_redirector.php?to=twitter&id={$this->b->d["message"]["from"]["id"]}",
					"callback_data" => "twd"
				]
			],
			[
				[
					"text" => "Follow & Like Our Fanspage",
					// "url" => "https://bot.cryptoveno.com/std_redirector.php?to=facebook&id={$this->b->d["message"]["from"]["id"]}",
					"callback_data" => "fbd"
				]
			],
			[
				[
					"text" => "Follow Our Medium",
					"callback_data" => "mdd"
					// "url" => "https://bot.cryptoveno.com/std_redirector.php?to=medium&id={$this->b->d["message"]["from"]["id"]}"
				]
			]
		];

		$pdo = DB::pdo();
		$st = $pdo->prepare("SELECT `task_id` FROM `users_task` WHERE `user_id` = :user_id;");
		$st->execute([":user_id" => $this->b->d["message"]["from"]["id"]]);
		while ($r = $st->fetch(PDO::FETCH_NUM)) {
			unset($tasks[$r[0] - 1]);
		}

		if (isset($tasks[1])) {
			array_splice($tasks, 2, 0, 
				[
					[
						[
							"text" => "Join Sponsor Channel",
							"url" => "https://t.me/AirdropDetective"
						]
					]
				]
			);
			var_dump($tasks);
		} else {
			var_dump("dd", $tasks);
		}

		$text = "Welcome to CRYPTOVENO Airdrop Bot.

Get free 100,000 VENO for joining our airdrop. You can also get additional 10,000 VENO for each referral you are invited.

Please follow the step by step bellow to participate in our airdrop. You must complete all steps to be eligible receive the rewards.

1️⃣ Join Telegram Group (25,000 VENO)
2️⃣ Join Our Channel & Sponsor (25,000 VENO)
3️⃣ Follow & Retweet Our Twitter (Optional) (20,000 VENO)
4️⃣ Follow & Like Our Fanspage (Optional) (15,000 VENO)
5️⃣ Follow Our Medium (Optional) (15,000 VENO)
6️⃣ Submit your detailed data by send /submit.

Terms and Conditions
1. You have to follow all the steps above to qualify.
2. Using multiple accounts, cheating, or spamming are not allowed and will result in a ban, bounty earnings forfeited, and entry disqualified.
3. We reserve the rights to make changes to any rules of this airdrop campaign at any time.
4. All airdrop tokens will be distributed after crowdsale.";
	

		$std = [
			"text" => $text,
			"chat_id" => $this->b->d["message"]["from"]["id"],
			"reply_to_message_id" => $this->b->d["message"]["message_id"],
			"parse_mode" => "HTML",
			"disable_web_page_preview" => true,
		];

		if (!$tasks) {
			$text = "You have completed all task!\n\nTerms and Conditions
1. You have to follow all the steps above to qualify.
2. Using multiple accounts, cheating, or spamming are not allowed and will result in a ban, bounty earnings forfeited, and entry disqualified.
3. We reserve the rights to make changes to any rules of this airdrop campaign at any time.
4. All airdrop tokens will be distributed after crowdsale.";
		} else {

			$tasks = array_values($tasks);

			$std["reply_markup"] = json_encode(
				[
					"inline_keyboard" => $tasks
				]
			);
		}

		$d = Exe::sendMessage($std);

	}
}


// 1️⃣ Join Telegram Group {$telegramGroup} (15,000 VENO)
// 2️⃣ Join Telegram Channel <a href=\"https://bot.cryptoveno.com/std_redirector.php?to=telegram_channel&id={$this->b->d["message"]["from"]["id"]}\">{$telegramChannel}</a> (15,000 VENO)
// 3️⃣ Follow & Retweet Our Twitter <a href=\"https://bot.cryptoveno.com/std_redirector.php?to=twitter&id={$this->b->d["message"]["from"]["id"]}\">{$twitterUrl}</a> (15,000 VENO)
// 4️⃣ Follow & Like Our Fanspage
// <a href=\"https://bot.cryptoveno.com/std_redirector.php?to=facebook&id={$this->b->d["message"]["from"]["id"]}\">{$facebookUrl}</a> (15,000 VENO) 
// 5️⃣ Follow Our Medium (Optional)
// <a href=\"https://bot.cryptoveno.com/std_redirector.php?to=medium&id={$this->b->d["message"]["from"]["id"]}\">{$mediumUrl}</a> (10,000 VENO)
// 6️⃣ Submit your detailed data by send /submit.
