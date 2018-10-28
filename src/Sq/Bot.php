<?php

namespace Sq;

use Sq\Responses\Start;

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
	 * Constructor.
	 */
	public function __construct(array $d)
	{
		$this->d = $d;
	}

	/**
	 * @return void
	 */
	public function responseRoutes(): void
	{
		if (!(
			isset($this->d["message"]["chat"]["type"]) &&
			$this->d["message"]["chat"]["type"] === "private"
		)) {
			return;
		}

		$text = isset($this->d["message"]["text"]) ? $this->d["message"]["text"] : null;

		if (preg_match("/^\/start$/Usi", $text)) {
			(new Start($this))->start();
			return;
		}
	}

	/**
	 * @return void
	 */
	public function run(): void
	{

	}
}
