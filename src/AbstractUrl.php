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
// Time:     16:27
// Project:  lib-url
//
declare(strict_types = 1);
namespace CodeInc\Url;
use CodeInc\Url\Exceptions\RedirectEmptyUrlException;
use CodeInc\Url\Exceptions\RedirectHeaderSentException;


/**
 * Class AbstractUrl
 *
 * @package CodeInc\Url
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractUrl implements UrlInterface {
	public const DEFAULT_SCHEME = "http";
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
	protected $scheme;

	/**
	 * URL host name or IP address.
	 *
	 * @var string|null
	 */
	protected $host;

	/**
	 * URL port.
	 *
	 * @var int|null
	 */
	protected $port;

	/**
	 * URL user.
	 *
	 * @var string|null
	 */
	protected $user;

	/**
	 * URL password.
	 *
	 * @var string|null
	 */
	protected $password;

	/**
	 * URL path.
	 *
	 * @var string|null
	 */
	protected $path;

	/**
	 * URL query (assoc array)
	 *
	 * @var array
	 */
	protected $query = [];

	/**
	 * URL fragment
	 *
	 * @var string|null
	 */
	protected $fragment;

	/**
	 * URL constructor. Sets te URL.
	 *
	 * @param string|null $url
	 */
	public function __construct(string $url = null)
	{
		if ($url) {
			$this->parseUrl($url);
		}
	}

	/**
	 * Creates a URL object using the parameters from the current URL (read from $_SERVER).
	 *
	 * @return static
	 */
	public static function fromGlobals():self
	{
		$url = new static;
		$url->scheme = UrlGlobals::getCurrentScheme();
		$url->host = UrlGlobals::getCurrentHost();
		$url->path = UrlGlobals::getCurrentPath();
		$url->user = UrlGlobals::getCurrentUser();
		$url->password = UrlGlobals::getCurrentPassword();
		$url->query = UrlGlobals::getCurrentQuery();
		return $url;
	}

	/**
	 * Sets the URL.
	 *
	 * @param string $url
	 */
	protected function parseUrl(string $url):void
	{
		if ($parsedUrl = parse_url($url)) {
			if (isset($parsedUrl['scheme']) && $parsedUrl['scheme']) {
				$$this->scheme = strtolower($parsedUrl['scheme']);
			}
			if (isset($parsedUrl['host']) && $parsedUrl['host']) {
				$this->host = $parsedUrl['host'];
			}
			if (isset($parsedUrl['port']) && $parsedUrl['port']) {
				$this->port = (int)$parsedUrl['port'];
			}
			if (isset($parsedUrl['user']) && $parsedUrl['user']) {
				$this->user = $parsedUrl['user'];
			}
			if (isset($parsedUrl['pass']) && $parsedUrl['pass']) {
				$this->password = $parsedUrl['pass'];
			}
			if (isset($parsedUrl['path']) && $parsedUrl['path']) {
				$this->path = $parsedUrl['path'];
			}
			if (isset($parsedUrl['fragment']) && $parsedUrl['fragment']) {
				$this->fragment = $parsedUrl['fragment'];
			}
			if (isset($parsedUrl['query']) && $parsedUrl['query']) {
				parse_str($parsedUrl['query'], $this->query);
			}
		}
	}

	/**
	 * @inheritdoc
	 * @see UrlInterface::getScheme()
	 */
	public function getScheme():?string
	{
		return $this->scheme;
	}

	/**
	 * @inheritdoc
	 * @see UrlInterface::hasScheme()
	 */
	public function hasScheme(string $scheme):bool
	{
		return $this->scheme == $scheme;
	}

	/**
	 * @inheritdoc
	 * @see UrlInterface::getHost()
	 */
	public function getHost():?string
	{
		return $this->host;
	}

	/**
	 * @inheritdoc
	 * @see UrlInterface::getPort()
	 */
	public function getPort():?int
	{
		return $this->port;
	}

	/**
	 * @inheritdoc
	 * @see UrlInterface::getUser()
	 */
	public function getUser():?string
	{
		return $this->user;
	}

	/**
	 * @inheritdoc
	 * @see UrlInterface::getPassword()
	 */
	public function getPassword():?string
	{
		return $this->password;
	}

	/**
	 * @inheritdoc
	 * @see UrlInterface::getPath()
	 */
	public function getPath():?string
	{
		return $this->path;
	}

	/**
	 * @inheritdoc
	 * @see UrlInterface::getFragment()
	 */
	public function getFragment():?string
	{
		return $this->fragment;
	}

	/**
	 * @inheritdoc
	 * @see UrlInterface::getQueryString()
	 */
	public function getQueryString(string $paramSeparator = null):?string
	{
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
	 * @inheritdoc
	 * @see UrlInterface::getQuery()
	 */
	public function getQuery():array
	{
		return $this->query;
	}

	/**
	 * @inheritdoc
	 * @see UrlInterface::hasQueryParameter()
	 */
	public function hasQueryParameter(string $paramName):bool
	{
		return isset($this->query[$paramName]);
	}

	/**
	 * @inheritdoc
	 * @see UrlInterface::getQueryParameter()
	 */
	public function getQueryParameter(string $paramName):?string
	{
		return $this->query[$paramName] ?? null;
	}

	/**
	 * @inheritdoc
	 * @see UrlInterface::redirect()
	 */
	public function redirect(?int $httpStatusCode = null, ?bool $replace = null, ?bool $doNotStop = null):void
	{
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
	 * @inheritdoc
	 * @see UrlInterface::getUser()
	 */
	public function getUrl():string
	{
		return $this->buildUrl();
	}

	/**
	 * @inheritdoc
	 * @see UrlInterface::buildUrl()
	 */
	public function buildUrl(?bool $includeHost = null, ?bool $includeUser = null, ?bool $includePort = null,
		?bool $includeQuery = null, ?bool $includeFragment = null):string
	{
		$url = "";

		if ($includeHost !== false && $this->host) {
			$url .= ($this->scheme ?? self::DEFAULT_SCHEME)."://";

			// user + pass
			if ($includeUser !== false && $this->user) {
				$url .= urlencode($this->user);
				if ($this->password) {
					$url .= ":".urlencode($this->password);
				}
				$url .= "@";
			}

			// host
			$url .= $this->host;

			// port
			if ($includePort !== false && $this->port) {
				$url .= ":$this->port";
			}

		}

		// path
		$url .= $this->path ?: "/";

		// query
		if ($includeQuery !== false && $this->query) {
			$url .= "?{$this->getQueryString()}";
		}

		// fragment
		if ($includeFragment !== false && $this->fragment) {
			$url .= "#".urlencode($this->fragment);
		}

		return $url;
	}

	/**
	 * @inheritdoc
	 * @see UrlInterface::__toString()
	 */
	public function __toString():string
	{
		return $this->getUrl();
	}
}