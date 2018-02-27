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
// Date:     29/11/2017
// Time:     14:08
// Project:  lib-url
//
declare(strict_types=1);
namespace CodeInc\Url;


/**
 * Class Url
 *
 * @package CodeInc\Url
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class Url extends AbstractUrl {
	/**
	 * Sets the URL scheme.
	 *
	 * @see Url::SCHEME_HTTPS
	 * @see Url::SCHEME_HTTP
	 * @param string $scheme
	 */
	public function setScheme(string $scheme):void
	{
		$this->scheme = strtolower($scheme);
	}

	/**
	 * Removes the scheme from the URL.
	 */
	public function removeScheme():void
	{
		$this->scheme = null;
	}

	/**
	 * Sets the host name or IP address.
	 *
	 * @param string $host
	 */
	public function setHost(string $host):void
	{
		$this->host = $host;
	}

	/**
	 * Removes the host from the URL.
	 */
	public function removeHost():void
	{
		$this->host = null;
	}

	/**
	 * Sets the host port number.
	 *
	 * @param int $port
	 */
	public function setPort(int $port):void
	{
		$this->port = $port;
	}

	/**
	 * Removes the port from the URL.
	 */
	public function removePort():void
	{
		$this->port = null;
	}
	
	/**
	 * Sets the user name.
	 *
	 * @param string $user
	 */
	public function setUser(string $user):void
	{
		$this->user = $user;
	}

	/**
	 * Removes the user from the URL.
	 */
	public function removeUser():void
	{
		$this->user = null;
	}

	/**
	 * Sets the user password.
	 *
	 * @param string $password
	 */
	public function setPassword(string $password):void
	{
		$this->password = $password;
	}

	/**
	 * Removes the password from the URL.
	 */
	public function removePassword():void
	{
		$this->password = null;
	}

	/**
	 * Sets the path.
	 *
	 * @param string $path
	 */
	public function setPath(string $path):void
	{
		$this->path = $path;
	}

	/**
	 * Removes the path from the URL.
	 */
	public function removePath():void
	{
		$this->path = null;
	}

	/**
	 * Sets the URL fragment.
	 *
	 * @param string $fragment
	 */
	public function setFragment(string $fragment):void
	{
		$this->fragment = $fragment;
	}

	/**
	 * Removes the fragment from the URL.
	 */
	public function removeFragment():void
	{
		$this->fragment = null;
	}

	/**
	 * Sets the query parameters from a string (parsed using parse_str()).
	 *
	 * @see parse_str()
	 * @param string $queryString
	 */
	public function setQueryString(string $queryString):void
	{
		parse_str($queryString, $this->query);
	}

	/**
	 * Replaces all the parameters with new ones.
	 *
	 * @param array $parameters
	 */
	public function setQuery(array $parameters):void
	{
		$this->removeQuery();
		$this->addQueryParameters($parameters);
	}

	/**
	 * Removes all query parameters.
	 */
	public function removeQuery():void
	{
		$this->query = [];
	}

	/**
	 * Add extra query parameters. Previously defined parameters are kept expect for duplicates which are replaced
	 * with the new parameters values.
	 *
	 * @param array $parameters
	 */
	public function addQueryParameters(array $parameters):void
	{
		foreach ($parameters as $paramName => $value) {
			$this->setQueryParameter((string)$paramName, $value !== null ? (string)$value : null);
		}
	}

	/**
	 * Sets a query parameter.
	 *
	 * @param string $paramName
	 * @param string|null $value
	 */
	public function setQueryParameter(string $paramName, string $value = null):void
	{
		$this->query[$paramName] = $value;
	}

	/**
	 * Removes a query parameter.
	 *
	 * @param string $paramName
	 */
	public function removeQueryParameter(string $paramName):void
	{
		unset($this->query[$paramName]);
	}
}