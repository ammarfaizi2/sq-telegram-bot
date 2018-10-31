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


$twitterUrl = htmlspecialchars(file_get_contents(BASEPATH."/storage/redirector/twitter.txt"), ENT_QUOTES, "UTF-8");
$facebookUrl = htmlspecialchars(file_get_contents(BASEPATH."/storage/redirector/facebook.txt"), ENT_QUOTES, "UTF-8");
$mediumUrl = htmlspecialchars(file_get_contents(BASEPATH."/storage/redirector/medium.txt"), ENT_QUOTES, "UTF-8");

$text = "Welcome to CRYPTOVENO Airdrop Bot.

Get free 70,000 VENO for joining our airdrop. You can also get additional 5000 VENO for each referral you are invited.

Please follow the step by step bellow to participate in our airdrop. You must complete all steps to be eligible receive the rewards.

1️⃣ Join Telegram Group
https://t.me/CRYPTOVENO (15,000 VENO)
2️⃣ Join Telegram Channel https://t.me/AnnouncedCRYPTOVENO (15,000 VENO)
3️⃣ Follow & Retweet Our Twitter <a href=\"https://veno.site/std_redirector.php?to=twitter&id={$this->b->d["message"]["from"]["id"]}\">{$twitterUrl}</a> (15,000 VENO)
4️⃣ Follow & Like Our Fanspage
<a href=\"https://veno.site/std_redirector.php?to=facebook&id={$this->b->d["message"]["from"]["id"]}\">{$facebookUrl}</a> (15,000 VENO) 
5️⃣ Follow Our Medium (Optional)
<a href=\"https://veno.site/std_redirector.php?to=medium&id={$this->b->d["message"]["from"]["id"]}\">{$mediumUrl}</a> (10,000 VENO)
6️⃣ Submit your detailed data.

Terms and Conditions
1. You have to follow all the steps above to qualify.
2. Using multiple accounts, cheating, or spamming are not allowed and will result in a ban, bounty earnings forfeited, and entry disqualified.
3. We reserve the rights to make changes to any rules of this airdrop campaign at any time.
4. All airdrop tokens will be distributed after crowdsale.";

		$d = Exe::sendMessage(
			[
				"text" => $text,
				"chat_id" => $this->b->d["message"]["from"]["id"],
				"reply_to_message_id" => $this->b->d["message"]["message_id"],
				"parse_mode" => "HTML"
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