<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
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
// Date:     19/02/2018
// Time:     12:35
// Project:  Url
//
namespace CodeInc\Url\Exceptions;
use CodeInc\Url\UrlInterface;
use Throwable;


/**
 * Class RedirectEmptyUrlException
 *
 * @package CodeInc\Url\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RedirectEmptyUrlException extends UrlException {
	/**
	 * RedirectEmptyUrlException constructor.
	 *
	 * @param UrlInterface $url
	 * @param null|Throwable $previous
	 */
	public function __construct(UrlInterface $url, ?Throwable $previous = null)
	{
		parent::__construct("Unable to redirect, the URL is empty", $url, $previous);
	}
}