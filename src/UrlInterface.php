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
// Date:     27/02/2018
// Time:     16:40
// Project:  lib-url
//
declare(strict_types = 1);
namespace CodeInc\Url;
use CodeInc\Url\Exceptions\RedirectEmptyUrlException;
use CodeInc\Url\Exceptions\RedirectHeaderSentException;


/**
 * Interface UrlInterface
 *
 * @package CodeInc\Url
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface UrlInterface {
	/**
	 * Returns the URL scheme.
	 *
	 * @return null|string
	 */
	public function getScheme():?string;

	/**
	 * Verifies if the URL has a given scheme.
	 *
	 * @param string $scheme
	 * @return bool
	 */
	public function hasScheme(string $scheme):bool;

	/**
	 * Returns the host name or IP address or null if not set.
	 *
	 * @return null|string
	 */
	public function getHost():?string;

	/**
	 * Returns the host port number.
	 *
	 * @return int|null
	 */
	public function getPort():?int;

	/**
	 * Returns the user name or null if not set.
	 *
	 * @return null|string
	 */
	public function getUser():?string;

	/**
	 * Returns the user password or null if not set.
	 *
	 * @return null|string
	 */
	public function getPassword():?string;

	/**
	 * Returns the path or null if not set.
	 *
	 * @return null|string
	 */
	public function getPath():?string;

	/**
	 * Returns the URL fragment or null if not set.
	 *
	 * @return null|string
	 */
	public function getFragment():?string;

	/**
	 * Returns the query parameters as a string or null if the query is empty.
	 *
	 * @see Url::DEFAULT_QUERY_PARAM_SEPARATOR
	 * @param string|null $paramSeparator (default: '&')
	 * @return string|null
	 */
	public function getQueryString(string $paramSeparator = null):?string;

	/**
	 * Returns the query parameters in an array.
	 *
	 * @return array
	 */
	public function getQuery():array;

	/**
	 * Verifies if a query parameter is set.
	 *
	 * @param string $paramName
	 * @return bool
	 */
	public function hasQueryParameter(string $paramName):bool;

	/**
	 * Returns the value of a query parameter or null if not set.
	 *
	 * @param string $paramName
	 * @return string|null
	 */
	public function getQueryParameter(string $paramName):?string;

	/**
	 * Redirects to the URL using a "location" header. The HTTP status code is modified (by default to 302).
	 *
	 * @see Url::DEFAULT_REDIRECT_STATUS_CODE
	 * @param int|null $httpStatusCode (default : 302)
	 * @param bool|null $replace (default: true)
	 * @param bool|null $doNotStop (default: false) Does not stop the script execution after the redirect
	 * @throws RedirectEmptyUrlException
	 * @throws RedirectHeaderSentException
	 */
	public function redirect(?int $httpStatusCode = null, ?bool $replace = null, ?bool $doNotStop = null):void;

	/**
	 * Returns the full URL (scheme + user + password + host + port + uri).
	 *
	 * @see Url::buildUrl()
	 * @return string
	 */
	public function getUrl():string;

	/**
	 * Builds a custom URL.
	 *
	 * @param bool|null $includeHost Includes the hostname (default: true)
	 * @param bool|null $includeUser Includes the user and password (defaut: true)
	 * @param bool|null $includePort Includes the port number (default: true)
	 * @param bool|null $includeQuery Incldues the query string (default: true)
	 * @param bool|null $includeFragment Includes the fragment (default: true)
	 * @return string
	 */
	public function buildUrl(?bool $includeHost = null, ?bool $includeUser = null, ?bool $includePort = null,
		?bool $includeQuery = null, ?bool $includeFragment = null):string;

	/**
	 * Returns the URL. Alias of getUrl()
	 *
	 * @see Url::getUrl()
	 * @return string
	 */
	public function __toString():string;
}