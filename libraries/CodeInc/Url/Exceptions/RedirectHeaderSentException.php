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
// Time:     12:34
// Project:  lib-url
//
namespace CodeInc\Url\Exceptions;
use CodeInc\Url\Url;
use Throwable;


/**
 * Class RedirectHeaderSentException
 *
 * @package CodeInc\Url\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RedirectHeaderSentException extends UrlException {
	/**
	 * HeaderSentException constructor.
	 *
	 * @param Url $url
	 * @param null|Throwable $previous
	 */
	public function __construct(Url $url, ?Throwable $previous = null) {
		parent::__construct("Unable to redirect to \"{$url->getUrl()}\", the headers have already been sent",
			$url, $previous);
	}
}