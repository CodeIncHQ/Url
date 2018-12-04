<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE - CONFIDENTIAL                                |
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
// Date:     29/11/2017
// Time:     14:08
// Project:  Url
//
declare(strict_types=1);
namespace CodeInc\Url;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;


/**
 * Class Url
 *
 * @package CodeInc\Url
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class Url implements UrlInterface, \IteratorAggregate
{
    public const DEFAULT_SCHEME = "http";
    public const DEFAULT_REDIRECT_STATUS_CODE = 302;
    public const DEFAULT_QUERY_PARAM_SEPARATOR = '&';

    private const PORTS_NUMBERS = [
        "ftp" => 21,
        "ssh" => 22,
        "sftp" => 22,
        "http" => 80,
        "https" => 443
    ];

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
     * Sets the URL.
     *
     * @param string $string
     * @param bool $scheme
     * @param bool $host
     * @param bool $port
     * @param bool $path
     * @param bool $fragment
     * @param bool $user
     * @param bool $password
     * @param bool $query
     * @return Url
     */
    public static function fromString(string $string, bool $scheme = true, bool $host = true, bool $port = true,
        bool $path = true, bool $fragment = true, bool $user = true, bool $password = true, bool $query = true):self
    {
        $url = new self;
        if ($parsedUrl = parse_url($string)) {
            if ($scheme && isset($parsedUrl['scheme']) && $parsedUrl['scheme']) {
                $url->scheme = strtolower($parsedUrl['scheme']);
            }
            if ($host && isset($parsedUrl['host']) && $parsedUrl['host']) {
                $url->host = $parsedUrl['host'];
            }
            if ($port && isset($parsedUrl['port']) && $parsedUrl['port']) {
                $url->port = (int)$parsedUrl['port'];
            }
            if ($user && isset($parsedUrl['user']) && $parsedUrl['user']) {
                $url->user = $parsedUrl['user'];
            }
            if ($password && isset($parsedUrl['pass']) && $parsedUrl['pass']) {
                $url->password = $parsedUrl['pass'];
            }
            if ($path && isset($parsedUrl['path']) && $parsedUrl['path']) {
                $url->path = $parsedUrl['path'];
            }
            if ($fragment && isset($parsedUrl['fragment']) && $parsedUrl['fragment']) {
                $url->fragment = $parsedUrl['fragment'];
            }
            if ($query && isset($parsedUrl['query']) && $parsedUrl['query']) {
                parse_str($parsedUrl['query'], $url->query);
            }
        }
        return $url;
    }

    /**
     * @param bool $scheme
     * @param bool $host
     * @param bool $port
     * @param bool $path
     * @param bool $user
     * @param bool $password
     * @param bool $query
     * @return Url
     */
    public static function fromGlobals(bool $scheme = true, bool $host = true, bool $port = true,
        bool $path = true, bool $user = true, bool $password = true, bool $query = true)
    {
        $url = new self;
        if ($scheme) {
            $url->setScheme(@$_SERVER["HTTPS"] == "on" ? "https" : "http");
        }
        if ($host && isset($_SERVER["HTTP_HOST"])) {
            if (($pos = strpos($_SERVER["HTTP_HOST"], ":")) !== false) {
                $url->setHost(substr($_SERVER["HTTP_HOST"], 0, $pos));
            }
            else {
                $url->setHost($_SERVER["HTTP_HOST"]);
            }
        }
        if ($port && isset($_SERVER["HTTP_HOST"]) && ($pos = strpos($_SERVER["HTTP_HOST"], ":")) !== false) {
            $url->setPort((int)substr($_SERVER["HTTP_HOST"], $pos + 1));
        }
        if ($path && isset($_SERVER["REQUEST_URI"])) {
            if (($pos = strpos($_SERVER["REQUEST_URI"], "?")) !== false) {
                $url->setPath(substr($_SERVER["REQUEST_URI"], 0, $pos));
            }
            else {
                $url->setPath($_SERVER["REQUEST_URI"]);
            }
        }
        if ($user && isset($_SERVER["PHP_AUTH_USER"])) {
            $url->setUser($_SERVER["PHP_AUTH_USER"]);
        }
        if ($password && isset($_SERVER["PHP_AUTH_PW"])) {
            $url->setPassword($_SERVER["PHP_AUTH_PW"]);
        }
        if ($query && isset($_SERVER["REQUEST_URI"]) && ($pos = strpos($_SERVER["REQUEST_URI"], "?")) !== false) {
            $url->setQueryString(substr($_SERVER["REQUEST_URI"], $pos + 1));
        }
        return $url;
    }

    /**
     * @param UriInterface $psr7Uri
     * @param bool $scheme
     * @param bool $host
     * @param bool $port
     * @param bool $path
     * @param bool $user
     * @param bool $password
     * @param bool $query
     * @return Url
     */
    public static function fromPsr7Uri(UriInterface $psr7Uri, bool $scheme = true, bool $host = true, bool $port = true,
        bool $path = true, bool $user = true, bool $password = true, bool $query = true):self
    {
        $url = new self;
        if ($scheme) $url->setScheme($psr7Uri->getScheme());
        if ($host) $url->setHost($psr7Uri->getHost());
        if ($port) $url->setPort($psr7Uri->getPort());
        if ($path) $url->setPath($psr7Uri->getPath());
        if (($user || $password) && $userInfo = $psr7Uri->getUserInfo()) {
            $userInfo = explode(":", $userInfo);
            if ($user) {
                $url->setUser($userInfo[0] ?? null);
            }
            if ($password) {
                $url->setPassword($userInfo[1] ?? null);
            }
        }
        if ($query) {
            $url->setQueryString($psr7Uri->getQuery());
        }
        return $url;
    }

    /**
     * @param ServerRequestInterface $psr7Request
     * @param bool $scheme
     * @param bool $host
     * @param bool $port
     * @param bool $path
     * @param bool $user
     * @param bool $password
     * @param bool $query
     * @return Url
     */
    public static function fromPsr7Request(ServerRequestInterface $psr7Request, bool $scheme = true, bool $host = true,
        bool $port = true, bool $path = true, bool $user = true, bool $password = true, bool $query = true):self
    {
        $url = self::fromPsr7Uri($psr7Request->getUri(), $scheme, $host, $port, $path, $user, $password, $query);
        $url->setQueryParameters($psr7Request->getQueryParams());
        return $url;
    }


    /**
     * Sets the URL scheme.
     *
     * @see Url::SCHEME_HTTPS
     * @see Url::SCHEME_HTTP
     * @param string|null $scheme
     * @return Url
     */
    public function setScheme(?string $scheme):self
    {
        $this->scheme = $scheme;
        return $this;
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
     * Sets the host name or IP address.
     *
     * @param string|null $host
     * @return Url
     */
    public function setHost(?string $host):self
    {
        $this->host = $host;
        return $this;
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
     * Sets the host port number.
     *
     * @param int|null $port
     * @return Url
     */
    public function setPort(?int $port):self
    {
        $this->port = $port;
        return $this;
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
     * Sets the user name.
     *
     * @param string|null $user
     * @return Url
     */
    public function setUser(?string $user):self
    {
        $this->user = $user;
        return $this;
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
     * Sets the user password.
     *
     * @param string|null $password
     * @return Url
     */
    public function setPassword(?string $password):self
    {
        $this->password = $password;
        return $this;
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
     * Sets the path.
     *
     * @param string|null $path
     * @return Url
     */
    public function setPath(?string $path):self
    {
        $this->path = $path;
        return $this;
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
     * Sets the URL fragment.
     *
     * @param string|null $fragment
     * @return Url
     */
    public function setFragment(?string $fragment):self
    {
        $this->fragment = $fragment;
        return $this;
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
     * Sets the query parameters from a string (parsed using parse_str()).
     *
     * @uses parse_str()
     * @param string $queryString
     * @return Url
     */
	public function setQueryString(string $queryString):self
	{
		parse_str($queryString, $this->query);
		return $this;
	}

    /**
     * Replaces all the parameters with new ones.
     *
     * @param array $parameters
     * @return Url
     */
	public function setQueryParameters(array $parameters):self
	{
		$this->query = [];
		$this->addQueryParameters($parameters);
		return $this;
	}

    /**
     * Add extra query parameters. Previously defined parameters are kept expect for duplicates which are replaced
     * with the new parameters values.
     *
     * @param array $parameters
     * @return Url
     */
	public function addQueryParameters(array $parameters):self
	{
		foreach ($parameters as $paramName => $value) {
			$this->setQueryParameter((string)$paramName, $value !== null ? (string)$value : null);
		}
		return $this;
	}

    /**
     * Sets a query parameter.
     *
     * @param string $paramName
     * @param string|null $value
     * @return Url
     */
	public function setQueryParameter(string $paramName, string $value = null):self
	{
		$this->query[$paramName] = $value;
		return $this;
	}

    /**
     * Removes a query parameter.
     *
     * @param string $paramName
     * @return Url
     */
    public function removeQueryParameter(string $paramName):self
    {
        unset($this->query[$paramName]);
        return $this;
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
     * @see UrlInterface::getQuery()
     */
    public function getQuery():array
    {
        return $this->query;
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
     * @see UrlInterface::getUser()
     */
    public function getUrl():string
    {
        return $this->buildUrl();
    }

    /**
     * @inheritdoc
     * @param bool $includeHost
     * @param bool $includeUser
     * @param bool $includePort
     * @param bool $includeQuery
     * @param bool $includeFragment
     * @return string
     */
    public function buildUrl(bool $includeHost = true, bool $includeUser = true, bool $includePort = true,
        bool $includeQuery = true, bool $includeFragment = true):string
    {
        $url = "";

        if ($includeHost && $this->host) {
            $scheme = $this->scheme ?? self::DEFAULT_SCHEME;
            $url .= "$scheme://";

            // user + pass
            if ($includeUser && $this->user) {
                $url .= urlencode($this->user);
                if ($this->password) {
                    $url .= ":".urlencode($this->password);
                }
                $url .= "@";
            }

            // host
            $url .= $this->host;

            // port
            if ($includePort && $this->port
                && (!isset(self::PORTS_NUMBERS[$scheme]) || $this->port != self::PORTS_NUMBERS[$scheme]))
            {
                $url .= ":$this->port";
            }

        }

        // path
        $url .= $this->path ?: "/";

        // query
        if ($includeQuery && $this->query) {
            $url .= "?{$this->getQueryString()}";
        }

        // fragment
        if ($includeFragment && $this->fragment) {
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

    /**
     * @inheritdoc
     * @return \ArrayIterator
     */
    public function getIterator():\ArrayIterator
    {
        return new \ArrayIterator($this->query);
    }

    /**
     * @inheritdoc
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset):bool
    {
        return $this->getQueryParameter((string)$offset) !== null;
    }

    /**
     * @inheritdoc
     * @param string $offset
     * @return string|null
     */
    public function offsetGet($offset):?string
    {
        return $this->getQueryParameter((string)$offset);
    }

    /**
     * @inheritdoc
     * @param string $offset
     * @param string|null $value
     */
    public function offsetSet($offset, $value):void
    {
        $this->setQueryParameter((string)$offset, $value !== null ? (string)$value : null);
    }

    /**
     * @inheritdoc
     * @param string $offset
     */
    public function offsetUnset($offset):void
    {
        $this->removeQueryParameter((string)$offset);
    }
}