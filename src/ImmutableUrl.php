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
// Time:     16:18
// Project:  lib-url
//
declare(strict_types = 1);
namespace CodeInc\Url;


/**
 * Class ImmutableUrl
 *
 * @package CodeInc\Url
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class ImmutableUrl extends AbstractUrl {
	/**
	 * Returns the URL with a new scheme.
	 *
	 * @see Url2::SCHEME_HTTPS
	 * @see Url2::SCHEME_HTTP
	 * @param string $scheme
	 * @return static
	 */
	public function withScheme(string $scheme)
	{
		$url = clone $this;
		$url->scheme = strtolower($scheme);
		return $url;
	}

	/**
	 * Returns the URL without a scheme.
	 *
	 * @return static
	 */
	public function withoutScheme()
	{
		$url = clone $this;
		$url->scheme = null;
		return $url;
	}

	/**
	 * Returns the URL with a host name or IP address.
	 *
	 * @param string $host
	 * @return static
	 */
	public function withHost(string $host)
	{
		$url = clone $this;
		$url->host = $host;
		return $url;
	}

	/**
	 * Returns the URL without a host the URL.
	 *
	 * @return static
	 */
	public function withoutHost()
	{
		$url = clone $this;
		$url->host = null;
		return $url;
	}

	/**
	 * Returns the URL with the port number.
	 *
	 * @param int $port
	 * @return static
	 */
	public function withPort(int $port)
	{
		$url = clone $this;
		$url->port = $port;
		return $url;
	}

	/**
	 * Returns the URL without a port URL.
	 *
	 * @return static
	 */
	public function withoutPort()
	{
		$url = clone $this;
		$url->port = null;
		return $url;
	}

	/**
	 * Returns the URL with the user name.
	 *
	 * @param string $user
	 * @return static
	 */
	public function withUser(string $user)
	{
		$url = clone $this;
		$url->user = $user;
		return $url;
	}


	/**
	 * Returns the URL without a user name.
	 *
	 * @return static
	 */
	public function withoutUser()
	{
		$url = clone $this;
		$url->user = null;
		return $url;
	}

	/**
	 * Returns the URL with the user password.
	 *
	 * @param string $password
	 * @return static
	 */
	public function withPassword(string $password)
	{
		$url = clone $this;
		$url->password = $password;
		return $url;
	}

	/**
	 * Returns the URL without a user password.
	 *
	 * @return static
	 */
	public function withoutPassword()
	{
		$url = clone $this;
		$url->password = null;
		return $url;
	}

	/**
	 * Returns the URL with the path.
	 *
	 * @param string $path
	 * @return static
	 */
	public function withPath(string $path)
	{
		$url = clone $this;
		$url->path = $path;
		return $url;
	}

	/**
	 * Returns the URL without a path.
	 *
	 * @return static
	 */
	public function removePath()
	{
		$url = clone $this;
		$url->path = null;
		return $url;
	}

	/**
	 * Returns the URL with the fragment.
	 *
	 * @param string $fragment
	 * @return static
	 */
	public function withFragment(string $fragment)
	{
		$url = clone $this;
		$url->fragment = $fragment;
		return $url;
	}

	/**
	 * Returns the URL without a fragment.
	 *
	 * @return static
	 */
	public function withoutFragment()
	{
		$url = clone $this;
		$url->fragment = null;
		return $url;
	}

	/**
	 * Returns the URL with the query string (parsed using parse_str()).
	 *
	 * @see parse_str()
	 * @param string $queryString
	 * @return static
	 */
	public function withQueryString(string $queryString)
	{
		$url = clone $this;
		parse_str($queryString, $url->query);
		return $url;
	}

	/**
	 * Returns the URL with the given query.
	 *
	 * @param array $parameters
	 * @return static
	 */
	public function withQuery(array $parameters)
	{
		$url = clone $this;
		$url->query = [];
		foreach ($parameters as $paramName => $value) {
			$url->query[(string)$paramName] = $value !== null ? (string)$value : null;
		}
		return $url;
	}

	/**
	 * Returns the URL with a query.
	 *
	 * @return static
	 */
	public function withoutQuery()
	{
		$url = clone $this;
		$url->query = [];
		return $url;
	}

	/**
	 * Returns the URL with the extra query parameters.
	 *
	 * @param array $parameters
	 * @return static
	 */
	public function withQueryParameters(array $parameters)
	{
		$url = clone $this;
		foreach ($parameters as $paramName => $value) {
			$url->query[(string)$paramName] = $value !== null ? (string)$value : null;
		}
		return $url;
	}

	/**
	 * Returns the URL with the extra query parameter.
	 *
	 * @param string $paramName
	 * @param string|null $value
	 * @return static
	 */
	public function withQueryParameter(string $paramName, string $value = null)
	{
		$url = clone $this;
		$url->query[$paramName] = $value;
		return $url;
	}

	/**
	 * Returns the URL without the query parameter.
	 *
	 * @param string $paramName
	 * @return static
	 */
	public function removeQueryParameter(string $paramName)
	{
		$url = clone $this;
		unset($url->query[$paramName]);
		return $url;
	}
}