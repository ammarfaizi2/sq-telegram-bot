<?php

namespace Sq;

use PDO;
use Sq\Responses\Info;
use Sq\Responses\Start;
use Sq\Responses\Submit;
use Sq\Responses\Handler;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @license MIT
 * @version 0.0.1
 * @package \Sq
 */
final class Bot
{
	/**
	 * @var array
	 */
	public $d;

	/**
	 * @var res
	 */
	private $h = NULL;

	/**
	 * @var string
	 */
	private $hdFile;

	/**
	 * Constructor.
	 */
	public function __construct(array $d)
	{
		// // Anti DoS
		// if (isset($d["message"]["from"]["id"])) {

		// 	$this->hdFile = BASEPATH."/storage/lock_files/{$d["message"]["from"]["id"]}.lock";

		// 	if (file_exists($this->hdFile)) {
		// 		$i = 0;
		// 		while (file_exists($this->hdFile)) {
		// 			$i++;
		// 			sleep(1);

		// 			if ($i === 10) {
		// 				exit(0);
		// 			}
		// 		}
		// 	}

		// 	$this->h = fopen($this->hdFile, "w");
		// 	flock($this->h, LOCK_EX);
		// 	fwrite($this->h, getmypid());
		// }

		$this->d = $d;
	}

	/** 
	 * Destructor.
	 */
	public function __destruct()
	{
		if (is_resource($this->h)) {
			fflush($this->h);
			flock($this->h, LOCK_UN);
			fclose($this->h);
			unlink($this->hdFile);
		}
	}

	/**
	 * @return void
	 */
	public function responseRoutes(): void
	{

		if (isset($this->d["callback_query"]["data"])) {
			switch ($this->d["callback_query"]["data"]) {
				case "jnd":
					// https://bot.cryptoveno.com/std_redirector.php?to=telegram_sponsor&id={$this->b->d["message"]["from"]["id"]}
					// https://bot.cryptoveno.com/std_redirector.php?to=telegram_channel&id={$this->b->d["message"]["from"]["id"]}

					Exe::sendMessage(
						[
							"text" => "<b>Join our channel and sponsor to finish this task!</b>",
							"chat_id" => $this->d["callback_query"]["message"]["chat"]["id"],
							"reply_markup" => json_encode(
								[
									"inline_keyboard" => [
										[
											[
												"text" => "Join Sponsor Channel",
												"url" => "https://bot.cryptoveno.com/std_redirector.php?to=telegram_sponsor&id={$this->d["callback_query"]["message"]["chat"]["id"]}"
											]
										],
										[
											[
												"text" => "Join Our Channel",
												"url" => "https://bot.cryptoveno.com/std_redirector.php?to=telegram_channel&id={$this->d["callback_query"]["message"]["chat"]["id"]}"
											]
										]
									]
								]
							),
							"parse_mode" => "HTML"
						]
					);

					return;
					break;
				case "sk_mdd":
					$r = "Send /submit to continue!";
					break;
				case "twd":
					$twitterUrl = htmlspecialchars(
						file_get_contents(BASEPATH."/storage/redirector/twitter.txt"),
						ENT_QUOTES,
						"UTF-8"
					);
					$r = "Follow & Retweet Our Twitter\n<a href=\"{$twitterUrl}\">Click HERE to go to our Twitter Account.</a>\n\nRetweet & Tag 5 friends\n<a href=\"https://twitter.com/CVenoWorld/status/1059474452256178176\">Go to Pinned Post</a>\n\n<b>Please send me your Twitter's Account link to continue!</b>\n\n<b>Reply to this message!</b>";
					break;
				case "fbd":
					$st = DB::pdo()->prepare("SELECT `twitter_link` FROM `users` WHERE `id` = :user_id LIMIT 1;");
					$st->execute([":user_id" => $this->d["callback_query"]["message"]["chat"]["id"]]);
					if ($st = $st->fetch(PDO::FETCH_NUM)) {
						if ($st[0]) {
							$facebookUrl = htmlspecialchars(
								file_get_contents(BASEPATH."/storage/redirector/facebook.txt"),
								ENT_QUOTES,
								"UTF-8"
							);
							$r = "Follow & Like Our Fanspage\n<a href=\"{$facebookUrl}\">Click HERE to go to our Facebook Account.</a>\n<b>Please send me your Facebook's Account link to continue</b>\n\n<b>Reply to this message!</b>";
						} else {
							$r = "You need to finish twitter task first before continue to facebook task!";
						}
					} else {
						$r = "You need to finish twitter task first before continue to facebook task!";
					}
					break;
				case "mdd":
					$st = DB::pdo()->prepare("SELECT `facebook_link` FROM `users` WHERE `id` = :user_id LIMIT 1;");
					$st->execute([":user_id" => $this->d["callback_query"]["message"]["chat"]["id"]]);
					if ($st = $st->fetch(PDO::FETCH_NUM)) {
						if ($st[0]) {
							$mediumUrl = htmlspecialchars(
								file_get_contents(BASEPATH."/storage/redirector/medium.txt"),
								ENT_QUOTES,
								"UTF-8"
							);
							$r = "Follow our Medium\n<a href=\"{$mediumUrl}\">Click HERE to go to our Medium.</a>\n<b>Please send me your Medium's Account link to continue</b>\n\n<b>Reply to this message!</b>";
						} else {
							$r = "You need to finish facebook task first before continue to medium task!";
						}
					} else {
						$r = "You need to finish facebook task first before continue to medium task!";
					}
					break;
				default:
					break;
			}


			$d = [
					"chat_id" => $this->d["callback_query"]["message"]["chat"]["id"],
					"text" => $r,
					"parse_mode" => "HTML",
				];
			if (!preg_match("/^You need.+/", $r)) {
				$d["reply_markup"] = json_encode(["force_reply" => true]);
			}

			Exe::sendMessage($d);

			return;
		}

		if (!(
			isset($this->d["message"]["chat"]["type"]) &&
			$this->d["message"]["chat"]["type"] === "private"
		)) {
			if (isset($this->d["message"]["new_chat_participant"]["id"], $this->d["message"]["chat"]["username"])) {
				$t = htmlspecialchars(file_get_contents(BASEPATH."/storage/redirector/telegram_group.txt"), ENT_QUOTES, "UTF-8");
				$t = explode("/", $t);
				$t = strtolower(end($t));
				if ($t === strtolower($this->d["message"]["chat"]["username"])) {
					$pdo = DB::pdo();
					$st = $pdo->prepare("SELECT `point` FROM `tasks` WHERE `id` = 1;");
					$st->execute();
					if ($st = $st->fetch(PDO::FETCH_NUM)) {
						if (addPoint(1, $this->d["message"]["from"]["id"])) {
							Exe::sendMessage(
								[
									"chat_id" => $this->d["message"]["chat"]["id"],
									"text" => "Welcome to CRYPTOVENO group, your VENO balance has been added!\n\n+{$st[0]} VENO",
									"reply_to_message_id" => $this->d["message"]["message_id"]
								]
							);
						}
					}
				}
			}

			return;
		}

		$text = isset($this->d["message"]["text"]) ? $this->d["message"]["text"] : null;

		$pdo = DB::pdo();
		$st = $pdo->prepare("SELECT `id`,`joined_at` FROM `users` WHERE `id` = :id LIMIT 1;");
		$st->execute([":id" => $this->d["message"]["from"]["id"]]);
		if (!($st = $st->fetch(PDO::FETCH_NUM))) {


			$st = $pdo->prepare("INSERT INTO `users` (`id`, `name`, `username`, `email`, `wallet`, `balance`, `twitter_link`, `facebook_link`, `medium_link`, `joined_at`, `started_at`) VALUES (:id, :name, :username, NULL, NULL, '0', NULL, NULL, NULL, NULL, :started_at);");
			$st->execute(
				[
					":id" => $this->d["message"]["from"]["id"],
					":name" => ($this->d["message"]["from"]["first_name"].(
						isset($this->d["message"]["from"]["last_name"]) ?
							" ".$this->d["message"]["from"]["last_name"] :
								""
					)),
					":username" => (
						isset($this->d["message"]["from"]["username"]) ? 
							"@".$this->d["message"]["from"]["username"] :
								NULL
					),
					":started_at" => date("Y-m-d H:i:s")
				]
			);

			if (preg_match("/(?:^\/start\s)(\d+)(?:$)/Usi", $text, $m)) {
				$text = "/start";
				$st = $pdo->prepare("SELECT `id` FROM `users` WHERE `id` = :id LIMIT 1;");
				$st->execute([":id" => ($m[1] = (int)trim($m[1]))]);
				if ($st->fetch(PDO::FETCH_ASSOC)) {
					$st = $pdo->prepare(
						"INSERT INTO `referred_users` (`user_id`,`referral_id`,`status`,`created_at`) VALUES (:user_id, :referral_id, :status, :created_at);"
					)->execute(
						[
							":user_id" => $this->d["message"]["from"]["id"],
							":referral_id" => $m[1],
							":status" => 0,
							":created_at" => date("Y-m-d H:i:s")
						]
					);
				}
			}
		} else {
			if ($st[1]) {
				define("rd_config", json_encode(
					[
						"keyboard" => [
							[
								[
									"text" => "Balance \xf0\x9f\x92\xb0",
								],
							],
							[	

								[
									"text" => "Support \xe2\x98\x8e\xef\xb8\x8f"
								]
							],
							[
								[
									"text" => "Tasks \xe2\x9a\x94\xef\xb8\x8f"
								]
							],
							[
								[
									"text" => "Buy Token \xf0\x9f\x92\xb4"
								]
							],
							[
								[
									"text" => "Referral Link \xf0\x9f\x91\xa5",
								],
								[
									"text" => "Social Media \xf0\x9f\x8c\x8d"
								]
							]
						]
					]
				));
			}
		}
		unset($st, $pdo);

		if ("Buy Token \xf0\x9f\x92\xb4" === $text) {
			Exe::sendMessage(
				[
					"chat_id" => $this->d["message"]["chat"]["id"],
					"text" => "How to buy CRYPTOVENO (VENO) ?
💠 Send min 0.01 Eth
💠 Contract : 0xeE8D611d2dEcc2AcB30191353A8e04496fC02090
💠 Always check ethgasstation
💠 You'll get instant token after send ETH

More detail :
https://tokensale.cryptoveno.com",
					"reply_to_message_id" => $this->d["message"]["message_id"]
				]
			);
			return;
		}


		if ("Balance \xf0\x9f\x92\xb0" === $text) {


			$pdo = DB::pdo();
			$st = $pdo->prepare("SELECT `balance` FROM `users` WHERE `id` = :user_id LIMIT 1;");
			$st->execute([":user_id" => $this->d["message"]["from"]["id"]]);
			$st2 = $pdo->prepare("SELECT COUNT(1) FROM `referred_users` WHERE `referral_id` = :user_id;");
			$st2->execute([":user_id" => $this->d["message"]["from"]["id"]]);
			if (($st = $st->fetch(PDO::FETCH_NUM)) && ($st2 = $st2->fetch(PDO::FETCH_NUM))) {
				$r = "<b>Your VENO balance is:</b> {$st[0]}\n\n<b>Referred Users:</b> {$st2[0]}";
			} else {
				$r = "An error occured!";
			}

			Exe::sendMessage(
				[
					"chat_id" => $this->d["message"]["chat"]["id"],
					"text" => $r,
					"reply_to_message_id" => $this->d["message"]["message_id"],
					"parse_mode" => "HTML"
				]
			);

			unset($st, $pdo);

			return;
		}

		if ("Support \xe2\x98\x8e\xef\xb8\x8f" === $text) {

			Exe::sendMessage(
				[
					"chat_id" => $this->d["message"]["chat"]["id"],
					"text" => "support@cryptoveno.com",
					"reply_to_message_id" => $this->d["message"]["message_id"],
					"parse_mode" => "HTML"
				]
			);

			return;
		}

		if ("Referral Link \xf0\x9f\x91\xa5" === $text) {
			Exe::sendMessage(
				[
					"chat_id" => $this->d["message"]["chat"]["id"],
					"text" => "<b>Your referral link is:</b> https://t.me/CryptoVenoBot?start={$this->d["message"]["from"]["id"]}",
					"reply_to_message_id" => $this->d["message"]["message_id"],
					"parse_mode" => "HTML"
				]
			);
			return;
		}

		if ("Social Media \xf0\x9f\x8c\x8d" === $text) {
			$telegramGroup = /*htmlspecialchars*/(file_get_contents(BASEPATH."/storage/redirector/telegram_group.txt")/*, ENT_QUOTES, "UTF-8"*/); 
			$telegramChannel = /*htmlspecialchars*/(file_get_contents(BASEPATH."/storage/redirector/telegram_channel.txt")/*, ENT_QUOTES, "UTF-8"*/); 
			$twitterUrl = /*htmlspecialchars*/(file_get_contents(BASEPATH."/storage/redirector/twitter.txt")/*, ENT_QUOTES, "UTF-8"*/);
			$facebookUrl = /*htmlspecialchars*/(file_get_contents(BASEPATH."/storage/redirector/facebook.txt")/*, ENT_QUOTES, "UTF-8"*/);
			$mediumUrl = /*htmlspecialchars*/(file_get_contents(BASEPATH."/storage/redirector/medium.txt")/*, ENT_QUOTES, "UTF-8"*/);
			$text = "Telegram Group:\n{$telegramGroup}\n\nTelegram Channel:\n{$telegramChannel}\n\nTwitter:\n{$twitterUrl}\n\nFacebook:\n{$facebookUrl}\n\nMedium:\n{$mediumUrl}";
			Exe::sendMessage(
				[
					"chat_id" => $this->d["message"]["chat"]["id"],
					"text" => $text,
					"reply_to_message_id" => $this->d["message"]["message_id"],
					"parse_mode" => "HTML"
				]
			);
			return;
		}

		if ("/start" === $text || "Tasks \xe2\x9a\x94\xef\xb8\x8f" === $text) {
			(new Start($this))->start();
			return;
		}

		if ("/submit" === $text) {

			$st = DB::pdo()->prepare("SELECT `facebook_link` FROM `users` WHERE `id` = :user_id LIMIT 1;");
			$st->execute([":user_id" => $this->d["message"]["from"]["id"]]);
			if ($st = $st->fetch(PDO::FETCH_NUM)) {
				if ($st[0]) {
					(new Submit($this))->submit();
					return;
				}
			}

			Exe::sendMessage(
				[
					"chat_id" => $this->d["message"]["chat"]["id"],
					"text" => "You need to finish the facebook task first before submit your detailed data!",
					"reply_to_message_id" => $this->d["message"]["message_id"]
				]
			);

			return;
		}

		if ("/info" === $text) {
			(new Info($this))->showInfo();
			return;
		}

		if ("/help" === $text) {
			$st = DB::pdo()->prepare("SELECT `facebook_link` FROM `users` WHERE `id` = :user_id LIMIT 1;");
			$st->execute([":user_id" => $this->d["message"]["from"]["id"]]);
			if ($st = $st->fetch(PDO::FETCH_NUM)) {
				if ($st[0]) {
					$d = [
							"chat_id" => $this->d["message"]["chat"]["id"],
							"text" => (
								"/info\t\tShow your information\n".
								"/set_wallet\t set/update your wallet address\n".
								"/set_email\t set/update your email address"
							),
							"reply_to_message_id" => $this->d["message"]["message_id"]
						];
					defined("rd_config") and $d["reply_markup"] = rd_config;
					Exe::sendMessage($d);
					return;
				}
			}
			(new Start($this))->start();
			return;
		}

		if ("/set_email" === $text) {
			$pdo = DB::pdo();
			$st = $pdo->prepare("SELECT `state` FROM `sessions` WHERE `user_id` = :user_id LIMIT 1;");
			$st->execute([":user_id" => $this->b->d["message"]["from"]["id"]]);

			if ($st = $st->fetch(PDO::FETCH_NUM)) {
				$state = $st[0];
			} else {
				$state = NULL;
			}

			if ($state === NULL) {
				Exe::sendMessage(
					[
						"chat_id" => $this->d["message"]["chat"]["id"],
						"text" => "Send /submit to register your email!",
						"reply_to_message_id" => $this->d["message"]["message_id"]
					]
				);
				return;
			} else {
				Exe::sendMessage(
					[
						"chat_id" => $this->d["message"]["chat"]["id"],
						"text" => "What is your email address?\n\nReply to this message!",
						"reply_to_message_id" => $this->d["message"]["message_id"],
						"reply_markup" => json_encode(["force_reply" => true])
					]
				);
				return;
			}
		}

		if ("/set_wallet" === $text) {
			$pdo = DB::pdo();
			$st = $pdo->prepare("SELECT `email` FROM `users` WHERE `id` = :user_id LIMIT 1;");
			$st->execute([":user_id" => $this->d["message"]["from"]["id"]]);

			if ($st = $st->fetch(PDO::FETCH_NUM)) {
				$state = $st[0];
			} else {
				$state = NULL;
			}

			if ($state === NULL) {
				Exe::sendMessage(
					[
						"chat_id" => $this->d["message"]["chat"]["id"],
						"text" => "You need to set your email first!",
						"reply_to_message_id" => $this->d["message"]["message_id"]
					]
				);
				return;
			} else {
				Exe::sendMessage(
					[
						"chat_id" => $this->d["message"]["chat"]["id"],
						"text" => "What is your wallet address?\n\nReply to this message!",
						"reply_to_message_id" => $this->d["message"]["message_id"],
						"reply_markup" => json_encode(["force_reply" => true])
					]
				);
				return;
			}
		}

		(new Handler($this))->handle();
	}

	/**
	 * @return void
	 */
	public function run(): void
	{
		$this->responseRoutes();
	}
}
