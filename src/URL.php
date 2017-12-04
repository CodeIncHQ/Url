<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE - CONFIDENTIAL                                |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.co for more information about licensing.  |
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
namespace CodeInc\URL;


/**
 * Class URL
 *
 * @package CodeInc\URL
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class URL {
	/**
	 * @var string
	 */
	private $scheme;

	/**
	 * @var string
	 */
	private $host;

	/**
	 * @var int
	 */
	private $port;

	/**
	 * @var string
	 */
	private $user;

	/**
	 * @var string
	 */
	private $pass;

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @var array
	 */
	private $query = [];

	/**
	 * @var string
	 */
	private $fragment;

	public function __construct(string $URL = null) {
		if ($URL) {
			$this->setURL($URL);
		}
	}

	public function setURL(string $URL) {
		if ($parsedURL = parse_url($URL)) {
			$this->scheme = $parsedURL['scheme'] ?? null;
			$this->host = $parsedURL['host'] ?? null;
			$this->port = isset($parsedURL['port']) ? (int)$parsedURL['port'] : null;
			$this->user = $parsedURL['user'] ?? null;
			$this->pass	= $parsedURL['pass'] ?? null;
			$this->path = $parsedURL['path'] ?? null;
			$this->fragment = $parsedURL['fragment'] ?? null;

			if (isset($parsedURL['query']) && $parsedURL['query']) {
				if (($this->query = parse_str($parsedURL['query'])) === null) {
					$this->query = [];
				}

			}
		}
	}

	/**
	 * @return string
	 */
	public function getScheme():string {
		return $this->scheme;
	}

	/**
	 * @return string
	 */
	public function getHost():string {
		return $this->host;
	}

	/**
	 * @return int
	 */
	public function getPort():int {
		return $this->port;
	}

	/**
	 * @return string
	 */
	public function getUser():string {
		return $this->user;
	}

	/**
	 * @return string
	 */
	public function getPass():string {
		return $this->pass;
	}

	/**
	 * @return string
	 */
	public function getPath():string {
		return $this->path;
	}

	/**
	 * @return string
	 */
	public function getFragment():string {
		return $this->fragment;
	}

	/**
	 * @return string
	 */
	public function getURL():string {
		$URL = "";
		if ($this->host) {
			$URL .= ($this->scheme ?? "http")."://";
			if ($this->user) {
				$URL .= urlencode($this->user);
				if ($this->pass) {
					$URL .= ":".urlencode($this->path);
				}
				$URL .= "@";
			}
			$URL .= $this->host;
		}
		if ($this->path) {
			$URL .= $this->path;
		}
		if ($this->query) {
			$URL .= "?".$this->getQueryString();
		}
		if ($this->fragment) {
			$URL .= "#".urlencode($this->fragment);
		}
		return $URL;
	}

	/**
	 * @return array
	 */
	public function getQuery():array {
		return $this->query;
	}

	/**
	 * @param string $parameter
	 * @param string|null $value
	 */
	public function addQueryParameter(string $parameter, string $value = null) {
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

	public function removeAllQueryParameters() {
		$this->query = [];
	}

	/**
	 * @param array $parameters
	 */
	public function addQueryParameters(array $parameters) {
		foreach ($parameters as $parameter => $value) {
			$this->addQueryParameter($parameter, $value);
		}
	}

	/**
	 * @param string|null $parameterSeparator
	 * @return string
	 */
	public function getQueryString(string $parameterSeparator = null):string {
		$queryString = "";
		foreach ($this->query as $parameter => $value) {
			if (!empty($queryString)) $queryString .= $parameterSeparator ?? "&";
			$queryString .= urlencode($parameter);
			if ($value) $queryString .= "=".urlencode($value);
		}
		return $queryString;
	}

	/**
	 * @param int|null $httpStatusCode
	 * @param bool|null $replace
	 */
	public function redirect(int $httpStatusCode = null, bool $replace = null) {
		header('Location: '.$this->getURL(), $replace ?? true, $httpStatusCode ?? 302);
		exit;
	}

	/**
	 * @return string
	 */
	public function __toString():string {
		return $this->getURL();
	}

	/**
	 * @return \ArrayIterator
	 */
	public function getIterator():\ArrayIterator {
		return new \ArrayIterator($this->query);
	}

	/**
	 * @param int|string $offset
	 * @param mixed $value
	 */
	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->query[] = $value;
		} else {
			$this->query[$offset] = $value;
		}
	}

	/**
	 * @param int|string $offset
	 * @return bool
	 */
	public function offsetExists($offset):bool {
		return array_key_exists($offset, $this->query);
	}

	/**
	 * @param int|string $offset
	 */
	public function offsetUnset($offset) {
		unset($this->query[$offset]);
	}

	/**
	 * @param int|string $offset
	 * @return mixed|null
	 */
	public function offsetGet($offset) {
		return $this->offsetExists($offset) ? $this->query[$offset] : null;
	}
}