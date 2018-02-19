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
// Project:  lib-gui
//
namespace CodeInc\Url;


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

	/**
	 * @var string|null
	 */
	private $scheme;

	/**
	 * @var string|null
	 */
	private $host;

	/**
	 * @var int|null
	 */
	private $port;

	/**
	 * @var string|null
	 */
	private $user;

	/**
	 * @var string|null
	 */
	private $password;

	/**
	 * @var string|null
	 */
	private $path;

	/**
	 * @var array
	 */
	private $query = [];

	/**
	 * @var string|null
	 */
	private $fragment;

	/**
	 * URL constructor.
	 *
	 * @param string|null $url
	 */
	public function __construct(string $url = null) {
		if ($url) {
			$this->setUrl($url);
		}
	}

	/**
	 * @return Url
	 */
	public static function factoryFromCurrentUrl():Url {
		$url = new Url();
		$url->setCurrentScheme();
		$url->setCurrentHost();
		$url->setCurrentPath();
		$url->setCurrentQueryString();
		$url->setCurrentUser();
		$url->setCurrentPassword();
		return $url;
	}

	/**
	 * Sets the URL.
	 *
	 * @param string $url
	 */
	protected function setUrl(string $url) {
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
	 * Sets the URL scheme.
	 *
	 * @param null|string $scheme
	 */
	public function setScheme(?string $scheme):void {
		$this->scheme = $scheme;
	}

	/**
	 * Sets the URL scheme using the current URI.
	 */
	public function setCurrentScheme():void {
		$this->setScheme(@$_SERVER["HTTPS"] == "on" ? self::SCHEME_HTTPS : self::SCHEME_HTTP);
	}

	/**
	 * @return null|string
	 */
	public function getHost():?string {
		return $this->host;
	}

	/**
	 * Sets the host name.
	 *
	 * @param null|string $host
	 */
	public function setHost(?string $host):void {
		$this->host = $host;
	}

	/**
	 * Sets the host name using the current URI. If the port number is part of the host name, it is
	 * also added to the URL.
	 *
	 * @return bool
	 */
	public function setCurrentHost():bool {
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
	 * @param int|null $port
	 */
	public function setPort(?int $port):void {
		$this->port = $port;
	}

	/**
	 * Sets the host port using the current URI.
	 *
	 * @return bool
	 */
	public function setCurrentPort():bool {
		if (isset($_SERVER["HTTP_HOST"]) && ($pos = strpos($_SERVER["HTTP_HOST"], ":")) !== false) {
			$this->setPort((int)substr($_SERVER["HTTP_HOST"], $pos + 1));
			return true;
		}
		return false;
	}

	/**
	 * Returns the user name.
	 *
	 * @return null|string
	 */
	public function getUser():?string {
		return $this->user;
	}

	/**
	 * Sets the user name.
	 *
	 * @param null|string $user
	 */
	public function setUser(?string $user):void {
		$this->user = $user;
	}

	/**
	 * Sets the user name using the current URL data.
	 *
	 * @return bool
	 */
	public function setCurrentUser():bool {
		if (isset($_SERVER["PHP_AUTH_USER"])) {
			$this->setUser($_SERVER["PHP_AUTH_USER"]);
			return true;
		}
		return false;
	}

	/**
	 * Returns the user password.
	 *
	 * @return null|string
	 */
	public function getPassword():?string {
		return $this->password;
	}

	/**
	 * Sets the user password.
	 *
	 * @param null|string $password
	 */
	public function setPassword(?string $password):void {
		$this->password = $password;
	}

	/**
	 * Sets the user password using the current URI.
	 *
	 * @return bool
	 */
	public function setCurrentPassword():bool {
		if (isset($_SERVER["PHP_AUTH_PW"])) {
			$this->setPassword($_SERVER["PHP_AUTH_PW"]);
			return true;
		}
		return false;
	}

	/**
	 * Returns the path.
	 *
	 * @return null|string
	 */
	public function getPath():?string {
		return $this->path;
	}

	/**
	 * Sets the path.
	 *
	 * @param null|string $path
	 */
	public function setPath(?string $path):void {
		$this->path = $path;
	}

	/**
	 * Sets the path using the current URI.
	 *
	 * @return bool
	 */
	public function setCurrentPath():bool {
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
	 * @return null|string
	 */
	public function getFragment():?string {
		return $this->fragment;
	}

	/**
	 * @param null|string $fragment
	 */
	public function setFragment(?string $fragment):void {
		$this->fragment = $fragment;
	}

	/**
	 * @return string
	 */
	public function __toString():string {
		return $this->getUrl();
	}

	/**
	 * Returns the query as a string.
	 *
	 * @param string|null $parameterSeparator
	 * @return string
	 */
	public function getQueryString(string $parameterSeparator = null):string {
		$queryString = "";
		foreach ($this->query as $parameter => $value) {
			if (!empty($queryString)) {
				$queryString .= $parameterSeparator ?: "&";
			}
			$queryString .= urlencode($parameter);
			if ($value) {
				$queryString .= "=".urlencode($value);
			}
		}
		return $queryString;
	}

	/**
	 * Sets the query from a string.
	 *
	 * @param string $queryString
	 */
	public function setQueryString(string $queryString):void {
		parse_str($queryString, $this->query);
	}

	/**
	 * Sets the query using the current URL.
	 *
	 * @return bool
	 */
	public function setCurrentQueryString():bool {
		if (isset($_SERVER["REQUEST_URI"]) && ($pos = strpos($_SERVER["REQUEST_URI"], "?")) !== false) {
			$this->setQueryString(substr($_SERVER["REQUEST_URI"], $pos + 1));
			return true;
		}
		return false;
	}

	/**
	 * @return array
	 */
	public function getQueryParameters():array {
		return $this->query;
	}

	/**
	 * @param string $parameter
	 * @param string|null $value
	 */
	public function addQueryParameter(string $parameter, string $value = null):void {
		$this->query[$parameter] = $value;
	}

	/**
	 * @param string $parameter
	 * @return bool
	 */
	public function hasQueryParameter(string $parameter):bool {
		return array_key_exists($parameter, $this->query);
	}

	/**
	 * @param string $parameter
	 * @return bool
	 */
	public function removeQueryParameter(string $parameter):bool {
		if ($this->hasQueryParameter($parameter)) {
			unset($this->query[$parameter]);
			return true;
		}
		return false;
	}

	public function removeAllQueryParameters():void
	{
		$this->query = [];
	}

	/**
	 * @param array $parameters
	 */
	public function addQueryParameters(array $parameters):void {
		foreach ($parameters as $parameter => $value) {
			$this->addQueryParameter($parameter, $value);
		}
	}

	/**
	 * @param int|null $httpStatusCode
	 * @param bool|null $replace
	 */
	public function redirect(int $httpStatusCode = null, bool $replace = null):void {
		header('Location: '.$this->getUrl(), $replace ?: true,
			$httpStatusCode ?: 302);
		exit;
	}

	/**
	 * @return null|string
	 */
	public function getUri():?string {
		$uri = "";
		if ($this->path) {
			$uri .= $this->path;
		}
		if ($this->query) {
			$uri .= "?".$this->getQueryString();
		}
		if ($this->fragment) {
			$uri .= "#".urlencode($this->fragment);
		}
		return $uri ?: null;
	}

	/**
	 * @return null|string
	 */
	public function getUrl():?string {
		$url = "";
		if ($this->host) {
			$url .= ($this->scheme ?? self::DEFAULT_SCHEME)."://";
			if ($this->user) {
				$url .= urlencode($this->user);
				if ($this->password) {
					$url .= ":".urlencode($this->path);
				}
				$url .= "@";
			}
			$url .= $this->host;
			if ($this->port) {
				$url .= ":$this->port";
			}
		}
		$url .= $this->getUri();
		return $url ?: null;
	}
}