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
use CodeInc\Url\Exceptions\RedirectEmptyUrlException;
use CodeInc\Url\Exceptions\RedirectHeaderSentException;


/**
 * Class Url
 *
 * @package CodeInc\Url
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class Url {
	public const SCHEME_HTTP = "http";
	public const SCHEME_HTTPS = "https";
	public const DEFAULT_SCHEME = self::SCHEME_HTTP;
	public const DEFAULT_REDIRECT_STATUS_CODE = 302;
	public const DEFAULT_QUERY_PARAM_SEPARATOR = '&';

	/**
	 * URL scheme.
	 *
	 * @see Url::SCHEME_HTTP
	 * @see Url::SCHEME_HTTPS
	 * @see Url::DEFAULT_SCHEME
	 * @var string|null
	 */
	private $scheme;

	/**
	 * URL host name or IP address.
	 *
	 * @var string|null
	 */
	private $host;

	/**
	 * URL port.
	 *
	 * @var int|null
	 */
	private $port;

	/**
	 * URL user.
	 *
	 * @var string|null
	 */
	private $user;

	/**
	 * URL password.
	 *
	 * @var string|null
	 */
	private $password;

	/**
	 * URL path.
	 *
	 * @var string|null
	 */
	private $path;

	/**
	 * URL query (assoc array)
	 *
	 * @var array
	 */
	private $query = [];

	/**
	 * URL fragment
	 *
	 * @var string|null
	 */
	private $fragment;

	/**
	 * URL constructor. Sets te URL.
	 *
	 * @param string|null $url
	 */
	public function __construct(string $url = null) {
		if ($url) {
			$this->parseUrl($url);
		}
	}

	/**
	 * Creates a URL object using the parameters from the current URL (read from $_SERVER).
	 *
	 * @return Url
	 */
	public static function fromCurrentUrl():Url {
		$url = new Url();
		$url->useCurrentScheme();
		$url->useCurrentHost();
		$url->useCurrentPath();
		$url->useCurrentQuery();
		$url->useCurrentUser();
		$url->useCurrentPassword();
		return $url;
	}

	/**
	 * Sets the URL.
	 *
	 * @param string $url
	 */
	public function parseUrl(string $url):void {
		if ($parsedUrl = parse_url($url)) {
			if (isset($parsedUrl['scheme']) && $parsedUrl['scheme']) {
				$this->setScheme($parsedUrl['scheme']);
			}
			if (isset($parsedUrl['host']) && $parsedUrl['host']) {
				$this->setHost($parsedUrl['host']);
			}
			if (isset($parsedUrl['port']) && $parsedUrl['port']) {
				$this->setPort((int)$parsedUrl['port']);
			}
			if (isset($parsedUrl['user']) && $parsedUrl['user']) {
				$this->setUser($parsedUrl['user']);
			}
			if (isset($parsedUrl['pass']) && $parsedUrl['pass']) {
				$this->setPassword($parsedUrl['pass']);
			}
			if (isset($parsedUrl['path']) && $parsedUrl['path']) {
				$this->setPath($parsedUrl['path']);
			}
			if (isset($parsedUrl['fragment']) && $parsedUrl['fragment']) {
				$this->setFragment($parsedUrl['fragment']);
			}
			if (isset($parsedUrl['query']) && $parsedUrl['query']) {
				$this->setQueryString($parsedUrl['query']);
			}
		}
	}

	/**
	 * Returns the URL scheme.
	 *
	 * @return null|string
	 */
	public function getScheme():?string {
		return $this->scheme;
	}

	/**
	 * Sets the URL scheme. The scheme value must be a valid protocol (not validated).
	 *
	 * @see Url::SCHEME_HTTPS
	 * @see Url::SCHEME_HTTP
	 * @param string $scheme
	 */
	public function setScheme(string $scheme):void {
		$this->scheme = $scheme;
	}

	/**
	 * Sets the URL scheme using the current URI (read from $_SERVER["HTTPS"]).
	 */
	public function useCurrentScheme():void {
		$this->setScheme(@$_SERVER["HTTPS"] == "on" ? self::SCHEME_HTTPS : self::SCHEME_HTTP);
	}

	/**
	 * Removes the scheme from the URL.
	 */
	public function removeScheme():void {
		$this->scheme = null;
	}

	/**
	 * Returns the host name or IP address or null if not set.
	 *
	 * @return null|string
	 */
	public function getHost():?string {
		return $this->host;
	}

	/**
	 * Sets the host name or IP address.
	 *
	 * @param string $host
	 */
	public function setHost(string $host):void {
		$this->host = $host;
	}

	/**
	 * Sets the host name using the current URI (read from $_SERVER["HTTP_HOST"]).
	 * If the port number is part of the host name, it is also added to the URL.
	 *
	 * @return bool
	 */
	public function useCurrentHost():bool {
		if (isset($_SERVER["HTTP_HOST"])) {
			if (($pos = strpos($_SERVER["HTTP_HOST"], ":")) !== false) {
				$this->setHost(substr($_SERVER["HTTP_HOST"], 0, $pos));
				$this->setPort((int)substr($_SERVER["HTTP_HOST"], $pos + 1));
			}
			else {
				$this->setHost($_SERVER["HTTP_HOST"]);
			}
			return true;
		}
		return false;
	}

	/**
	 * Removes the host from the URL.
	 */
	public function removeHost():void {
		$this->host = null;
	}

	/**
	 * Returns the host port number.
	 *
	 * @return int|null
	 */
	public function getPort():?int {
		return $this->port;
	}

	/**
	 * Sets the host port number.
	 *
	 * @param int $port
	 */
	public function setPort(int $port):void {
		$this->port = $port;
	}

	/**
	 * Sets the host port using the current URI (read from $_SERVER["HTTP_HOST"]).
	 *
	 * @return bool
	 */
	public function useCurrentPort():bool {
		if (isset($_SERVER["HTTP_HOST"]) && ($pos = strpos($_SERVER["HTTP_HOST"], ":")) !== false) {
			$this->setPort((int)substr($_SERVER["HTTP_HOST"], $pos + 1));
			return true;
		}
		return false;
	}

	/**
	 * Removes the port from the URL.
	 */
	public function removePort():void {
		$this->port = null;
	}
	
	/**
	 * Returns the user name or null if not set.
	 *
	 * @return null|string
	 */
	public function getUser():?string {
		return $this->user;
	}

	/**
	 * Sets the user name.
	 *
	 * @param string $user
	 */
	public function setUser(string $user):void {
		$this->user = $user;
	}

	/**
	 * Sets the user name using the current URL (read from $_SERVER["PHP_AUTH_USER"]).
	 *
	 * @return bool
	 */
	public function useCurrentUser():bool {
		if (isset($_SERVER["PHP_AUTH_USER"])) {
			$this->setUser($_SERVER["PHP_AUTH_USER"]);
			return true;
		}
		return false;
	}

	/**
	 * Removes the user from the URL.
	 */
	public function removeUser():void {
		$this->user = null;
	}

	/**
	 * Returns the user password or null if not set.
	 *
	 * @return null|string
	 */
	public function getPassword():?string {
		return $this->password;
	}

	/**
	 * Sets the user password.
	 *
	 * @param string $password
	 */
	public function setPassword(string $password):void {
		$this->password = $password;
	}

	/**
	 * Sets the user password using the current URI (read from $_SERVER["PHP_AUTH_PW"]).
	 *
	 * @return bool
	 */
	public function useCurrentPassword():bool {
		if (isset($_SERVER["PHP_AUTH_PW"])) {
			$this->setPassword($_SERVER["PHP_AUTH_PW"]);
			return true;
		}
		return false;
	}

	/**
	 * Removes the password from the URL.
	 */
	public function removePassword():void {
		$this->password = null;
	}

	/**
	 * Returns the path or null if not set.
	 *
	 * @return null|string
	 */
	public function getPath():?string {
		return $this->path;
	}

	/**
	 * Sets the path.
	 *
	 * @param string $path
	 */
	public function setPath(string $path):void {
		$this->path = $path;
	}

	/**
	 * Sets the path using the current URI (read from $_SERVER["REQUEST_URI"]).
	 *
	 * @return bool
	 */
	public function useCurrentPath():bool {
		if (isset($_SERVER["REQUEST_URI"])) {
			if (($pos = strpos($_SERVER["REQUEST_URI"], "?")) !== false) {
				$this->setPath(substr($_SERVER["REQUEST_URI"], 0, $pos));
			}
			else {
				$this->setPath($_SERVER["REQUEST_URI"]);
			}
			return true;
		}
		return false;
	}

	/**
	 * Removes the path from the URL.
	 */
	public function removePath():void {
		$this->path = null;
	}

	/**
	 * Returns the URL fragment or null if not set.
	 *
	 * @return null|string
	 */
	public function getFragment():?string {
		return $this->fragment;
	}

	/**
	 * Sets the URL fragment.
	 *
	 * @param string $fragment
	 */
	public function setFragment(string $fragment):void {
		$this->fragment = $fragment;
	}

	/**
	 * Removes the fragment from the URL.
	 */
	public function removeFragment():void {
		$this->fragment = null;
	}

	/**
	 * Returns the query parameters as a string or null if the query is empty.
	 *
	 * @see Url::DEFAULT_QUERY_PARAM_SEPARATOR
	 * @param string|null $paramSeparator (default: '&')
	 * @return string|null
	 */
	public function getQueryString(string $paramSeparator = null):?string {
		$queryString = "";
		foreach ($this->query as $parameter => $value) {
			if (!empty($queryString)) {
				$queryString .= $paramSeparator ?: self::DEFAULT_QUERY_PARAM_SEPARATOR;
			}
			$queryString .= urlencode($parameter);
			if ($value) {
				$queryString .= "=".urlencode($value);
			}
		}
		return $queryString ?: null;
	}

	/**
	 * Sets the query parameters from a string (parsed using parse_str()).
	 *
	 * @see parse_str()
	 * @param string $queryString
	 */
	public function setQueryString(string $queryString):void {
		parse_str($queryString, $this->query);
	}

	/**
	 * Sets the query parameters using the current URL. The parameters are added using the URI read from
	 * $_SERVER["REQUEST_URI"] and not using the $_GET array.
	 *
	 * @return bool
	 */
	public function useCurrentQuery():bool {
		if (isset($_SERVER["REQUEST_URI"]) && ($pos = strpos($_SERVER["REQUEST_URI"], "?")) !== false) {
			$this->setQueryString(substr($_SERVER["REQUEST_URI"], $pos + 1));
			return true;
		}
		return false;
	}

	/**
	 * Returns the query parameters in an array.
	 *
	 * @return array
	 */
	public function getQuery():array {
		return $this->query;
	}

	/**
	 * Replaces all the parameters with new ones.
	 *
	 * @param array $parameters
	 */
	public function setQuery(array $parameters):void {
		$this->removeQuery();
		$this->addQueryParameters($parameters);
	}

	/**
	 * Removes all query parameters.
	 */
	public function removeQuery():void {
		$this->query = [];
	}

	/**
	 * Add extra query parameters. Previously defined parameters are kept expect for duplicates which are replaced
	 * with the new parameters values.
	 *
	 * @param array $parameters
	 */
	public function addQueryParameters(array $parameters):void {
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
	public function setQueryParameter(string $paramName, string $value = null):void {
		$this->query[$paramName] = $value;
	}

	/**
	 * Verifies if a query parameter is set.
	 *
	 * @param string $paramName
	 * @return bool
	 */
	public function hasQueryParameter(string $paramName):bool {
		return isset($this->query[$paramName]);
	}

	/**
	 * Returns the value of a query parameter or null if not set.
	 *
	 * @param string $paramName
	 * @return string|null
	 */
	public function getQueryParameter(string $paramName):?string {
		return $this->query[$paramName] ?? null;
	}

	/**
	 * Removes a query parameter.
	 *
	 * @param string $paramName
	 */
	public function removeQueryParameter(string $paramName):void {
		unset($this->query[$paramName]);
	}

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
	public function redirect(int $httpStatusCode = null, bool $replace = null, bool $doNotStop = null):void {
		// checking...
		if (($url = $this->getUrl()) === null) {
			throw new RedirectEmptyUrlException($this);
		}
		if (headers_sent()) {
			throw new RedirectHeaderSentException($this);
		}

		// redirecting...
		header("Location: $url", $replace ?: true,
			$httpStatusCode ?: self::DEFAULT_REDIRECT_STATUS_CODE);
		if ($doNotStop !== true) {
			exit;
		}
	}

	/**
	 * Returns the full URL (scheme + user + password + host + port + uri).
	 *
	 * @see Url::buildUrl()
	 * @return string
	 */
	public function getUrl():string {
		return $this->buildUrl();
	}

	/**
	 * Builds a custom URL.
	 *
	 * @param bool|null $host Includes the hostname (default: true)
	 * @param bool|null $user Includes the user and password (defaut: true)
	 * @param bool|null $port Includes the port number (default: true)
	 * @param bool|null $query Incldues the query string (default: true)
	 * @param bool|null $fragment Includes the fragment (default: true)
	 * @return string
	 */
	public function buildUrl(bool $host = null, bool $user = null, bool $port = null, bool $query = null, bool $fragment = null):string {
		$url = "";

		if ($host !== false && $this->host) {
			$url .= ($this->scheme ?? self::DEFAULT_SCHEME)."://";

			// user + pass
			if ($user !== false && $this->user) {
				$url .= urlencode($this->user);
				if ($this->password) {
					$url .= ":".urlencode($this->password);
				}
				$url .= "@";
			}

			// host
			$url .= $this->host;

			// port
			if ($port !== false && $this->port) {
				$url .= ":$this->port";
			}

		}

		// path
		$url .= $this->path ?: "/";

		// query
		if ($query !== false && $this->query) {
			$url .= "?{$this->getQueryString()}";
		}

		// fragment
		if ($fragment !== false && $this->fragment) {
			$url .= "#".urlencode($this->fragment);
		}

		return $url;
	}

	/**
	 * Returns the URL. Alias of getUrl()
	 *
	 * @see Url::getUrl()
	 * @return string
	 */
	public function __toString():string {
		return $this->getUrl();
	}

	/**
	 * Returns a query parameter value. Alias of getQueryParameter().
	 *
	 * @see Url::getQueryParameter()
	 * @param $paramName
	 * @return null|string
	 */
	public function __get($paramName):?string {
		return $this->getQueryParameter((string)$paramName);
	}

	/**
	 * Sets a query parameter value. Alias of setQueryParameter().
	 *
	 * @see Url::setQueryParameter()
	 * @param $paramName
	 * @param $value
	 */
	public function __set($paramName, $value):void {
		$this->setQueryParameter($paramName, $value !== null ? (string)$value : null);
	}
}