<?php

namespace Sq;

use PDO;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @license MIT
 * @version 0.0.1
 * @package \Sq
 */
class DB
{
	/**
	 * @var static self
	 */
	private static $me;

	/**
	 * @var \PDO
	 */
	private $pdo;

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->pdo = new PDO(
			"mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME,
			DB_USER,
			DB_PASS,
			[
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			]
		);
	}

	/**
	 * @return \Sq\DB (self)
	 */
	public static function getInstance(): DB
	{
		if (!(self::$me instanceof DB)) {
			self::$me = new self;
		}
		return self::$me;
	}

	/**
	 * @return \PDO
	 */
	public static function pdo(): PDO
	{
		return self::getInstance()->pdo;
	}
}
