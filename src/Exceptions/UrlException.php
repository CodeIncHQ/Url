<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE - CONFIDENTIAL                                |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material  is strictly forbidden unless prior   |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     04/12/2017
// Time:     18:28
// Project:  lib-url
//
namespace CodeInc\Url\Exceptions;
use CodeInc\Url\Url;
use Throwable;


/**
 * Class UrlException
 *
 * @package CodeInc\Url\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class UrlException extends \Exception {
	/**
	 * @var Url
	 */
	private $url;

	/**
	 * UrlException constructor.
	 *
	 * @param string $message
	 * @param Url $url
	 * @param Throwable|null $previous
	 */
	public function __construct(string $message = "", Url $url, Throwable $previous = null) {
		$this->url = $url;
		parent::__construct($message, 0, $previous);
	}

	/**
	 * Returns the parent URL object.
	 *
	 * @return Url
	 */
	public function getUrl():Url {
		return $this->url;
	}
}