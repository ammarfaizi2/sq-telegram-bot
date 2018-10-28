<?php

namespace Sq;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @license MIT
 * @version 0.0.1
 * @package \Sq
 */
abstract class ResponseFoundation
{
	/**
	 * @var \Sq\Bot
	 */
	protected $b;

	/**
	 * @param \Sq\Bot $b
	 *
	 * Constructor.
	 */
	public function __construct(Bot $b)
	{
		$this->b = $b;
	}
}
