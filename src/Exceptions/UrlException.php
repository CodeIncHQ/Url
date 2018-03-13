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
// Project:  Url
//
namespace CodeInc\Url\Exceptions;
use CodeInc\Url\UrlInterface;
use Throwable;


/**
 * Class UrlException
 *
 * @package CodeInc\Url\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class UrlException extends \Exception {
	/**
	 * @var UrlInterface
	 */
	private $url;

	/**
	 * UrlException constructor.
	 *
	 * @param string $message
	 * @param UrlInterface $url
	 * @param Throwable|null $previous
	 */
	public function __construct(string $message = "", UrlInterface $url, Throwable $previous = null) {
		$this->url = $url;
		parent::__construct($message, 0, $previous);
	}

	/**
	 * Returns the parent URL object.
	 *
	 * @return UrlInterface
	 */
	public function getUrl():UrlInterface {
		return $this->url;
	}
}