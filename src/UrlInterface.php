<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2018 - Code Inc. SAS - All Rights Reserved.           |
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
// Project:  Url
//
declare(strict_types = 1);
namespace CodeInc\Url;


/**
 * Interface UrlInterface
 *
 * @package CodeInc\Url
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface UrlInterface extends \ArrayAccess, \Traversable
{
	/**
	 * Returns the URL scheme.
	 *
	 * @return null|string
	 */
	public function getScheme():?string;

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
	 * Returns the value of a query parameter or null if not set.
	 *
	 * @param string $paramName
	 * @return string|null
	 */
	public function getQueryParameter(string $paramName):?string;

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
	 * @param bool $includeHost Includes the hostname (default: true)
	 * @param bool $includeUser Includes the user and password (defaut: true)
	 * @param bool $includePort Includes the port number (default: true)
	 * @param bool $includeQuery Incldues the query string (default: true)
	 * @param bool $includeFragment Includes the fragment (default: true)
	 * @return string
	 */
	public function buildUrl(bool $includeHost = true, bool $includeUser = true, bool $includePort = true,
        bool $includeQuery = true, bool $includeFragment = true):string;

	/**
	 * Returns the URL. Alias of getUrl()
	 *
	 * @see Url::getUrl()
	 * @return string
	 */
	public function __toString():string;
}