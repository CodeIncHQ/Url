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
class Url implements UrlInterface
{
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
     * URL user and password separated by ':'.
     *
     * @var string|null
     */
    protected $userInfo;

    /**
     * URL path.
     *
     * @var string|null
     */
    protected $path;

    /**
     * URL query parameters.
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
     * @return static
     */
    public static function fromString(string $string):self
    {
        $url = new static;
        if ($parsedUrl = parse_url($string)) {
            if (isset($parsedUrl['scheme'])) {
                $url->setScheme($parsedUrl['scheme']);
            }
            if (isset($parsedUrl['host'])) {
                $url->setHost($parsedUrl['host']);
            }
            if (isset($parsedUrl['port'])) {
                $url->setPort($parsedUrl['port']);
            }
            if (isset($parsedUrl['user'])) {
                $url->setUserInfo($parsedUrl['user'], $parsedUrl['pass'] ?? null);
            }
            if (isset($parsedUrl['path'])) {
                $url->setPath($parsedUrl['path']);
            }
            if (isset($parsedUrl['fragment'])) {
                $url->setFragment($parsedUrl['fragment']);
            }
            if (isset($parsedUrl['query']) && $parsedUrl['query']) {
                $url->setQuery($parsedUrl['query']);
            }
        }
        return $url;
    }

    /**
     * @param bool $scheme
     * @return static
     */
    public static function fromGlobals(bool $scheme = true)
    {
        $url = new static();
        if ($scheme) {
            $url->setScheme(@$_SERVER["HTTPS"] == "on" ? "https" : "http");
        }
        if (isset($_SERVER["HTTP_HOST"])) {
            if (($pos = strpos($_SERVER["HTTP_HOST"], ":")) !== false) {
                $url->setHost(substr($_SERVER["HTTP_HOST"], 0, $pos));
            }
            else {
                $url->setHost($_SERVER["HTTP_HOST"]);
            }
        }
        if (isset($_SERVER["HTTP_HOST"]) && ($pos = strpos($_SERVER["HTTP_HOST"], ":")) !== false) {
            $url->setPort(substr($_SERVER["HTTP_HOST"], $pos + 1));
        }
        if (isset($_SERVER["REQUEST_URI"])) {
            if (($pos = strpos($_SERVER["REQUEST_URI"], "?")) !== false) {
                $url->setPath(substr($_SERVER["REQUEST_URI"], 0, $pos));
            }
            else {
                $url->setPath($_SERVER["REQUEST_URI"]);
            }
        }
        if (isset($_SERVER["PHP_AUTH_USER"])) {
            $url->setUserInfo($_SERVER["PHP_AUTH_USER"], $_SERVER["PHP_AUTH_PW"] ?? null);
        }
        if (isset($_SERVER["REQUEST_URI"]) && ($pos = strpos($_SERVER["REQUEST_URI"], "?")) !== false) {
            $url->setQuery(substr($_SERVER["REQUEST_URI"], $pos + 1));
        }
        return $url;
    }

    /**
     * @param UriInterface $uri
     * @return static
     */
    public static function fromPsr7Uri(UriInterface $uri):self
    {
        $url = new static();
        if ($scheme = $uri->getScheme()) {
            $url->setScheme($scheme);
        }
        if ($host = $uri->getHost()) {
            $url->setHost($host);
        }
        if ($port = $uri->getPort()) {
            $url->setPort($port);
        }
        if ($path = $uri->getPath()) {
            $url->setPath($path);
        }
        if ($userInfo = $uri->getUserInfo()) {
            $url->setUserInfo($userInfo);
        }
        if ($query = $uri->getQuery()) {
            $url->setQuery($uri->getQuery());
        }
        return $url;
    }

    /**
     * @param ServerRequestInterface $request
     * @return static
     */
    public static function fromPsr7Request(ServerRequestInterface $request):self
    {
        $url = static::fromPsr7Uri($request->getUri());
        $url->setQuery($request->getQueryParams());
        return $url;
    }


    /**
     * @inheritdoc
     * @return string|null
     */
    public function getScheme():?string
    {
        return $this->scheme;
    }

    /**
     * Sets the URL scheme ('http', 'https', etc.).
     *
     * @param string|null $scheme
     */
    protected function setScheme($scheme):void
    {
        $this->scheme = $scheme ? strtolower((string)$scheme) : null;
    }

    /**
     * @inheritdoc
     * @param string $scheme
     * @return static
     */
    public function withScheme($scheme):UrlInterface
    {
        $url = clone $this;
        $url->setScheme($scheme);
        return $url;
    }

    /**
     * @inheritdoc
     * @return static
     */
    public function withoutScheme():UrlInterface
    {
        $url = clone $this;
        $url->setScheme(null);
        return $url;
    }

    /**
     * @inheritdoc
     * @return string|null
     */
    public function getHost():?string
    {
        return $this->host;
    }

    /**
     * Sets the host name or IP address.
     *
     * @param string|null $host
     */
    protected function setHost($host):void
    {
        $this->host = $host ? (string)$host : null;
    }

    /**
     * @inheritdoc
     * @param string|null $host
     * @return static
     */
    public function withHost($host):UrlInterface
    {
        $url = clone $this;
        $url->setHost($host);
        return $url;
    }

    /**
     * @inheritdoc
     * @return static
     */
    public function withoutHost():UrlInterface
    {
        $url = clone $this;
        $url->setHost(null);
        return $url;
    }

    /**
     * @inheritdoc
     * @return int|null
     */
    public function getPort():?int
    {
        return $this->port;
    }

    /**
     * Sets the host port number.
     *
     * @param int|null $port
     */
    protected function setPort($port):void
    {
        $this->port = $port ? (int)$port : null;
    }

    /**
     * @inheritdoc
     * @param int|null $port
     * @return static
     */
    public function withPort($port):UrlInterface
    {
        $url = clone $this;
        $url->setPort($port);
        return $url;
    }

    /**
     * @inheritdoc
     * @return static
     */
    public function withoutPort():UrlInterface
    {
        $url = clone $this;
        $url->setPort(null);
        return $url;
    }

    /**
     * @inheritdoc
     * @return string|null
     */
    public function getUserInfo():?string
    {
        return $this->userInfo;
    }

    /**
     * @inheritdoc
     * @return string|null
     */
    public function getUser():?string
    {
        if ($this->userInfo) {
            return explode(':', $this->userInfo)[0] ?? null;
        }
        return null;
    }

    /**
     * @inheritdoc
     * @return string|null
     */
    public function getPassword():?string
    {
        if ($this->userInfo) {
            return explode(':', $this->userInfo)[1] ?? null;
        }
        return null;
    }

    /**
     * Sets the user and password.
     *
     * @param string|null $user
     * @param string|null $password
     */
    protected function setUserInfo($user, $password = null):void
    {
        if ($user) {
            $this->userInfo = (string)$user;
            if ($password) {
                $this->userInfo .= ':'.(string)$password;
            }
        }
    }

    /**
     * @inheritdoc
     * @param string|null $user
     * @param string|null $password
     * @return static
     */
    public function withUserInfo($user, $password = null):UrlInterface
    {
        $url = clone $this;
        $url->setUserInfo($user, $password);
        return $url;
    }

    /**
     * @inheritdoc
     * @return static
     */
    public function withoutUserInfo():UrlInterface
    {
        $url = clone $this;
        $url->setUserInfo(null);
        return $url;
    }

    /**
     * @return string|null
     */
    public function getPath():?string
    {
        return $this->path;
    }

    /**
     * Sets the path.
     *
     * @param string|null $path
     */
    protected function setPath($path):void
    {
        $this->path = $path ? (string)$path : null;
    }

    /**
     * @inheritdoc
     * @param string|null $path
     * @return static
     */
    public function withPath($path):UrlInterface
    {
        $url = clone $this;
        $url->setPath($path);
        return $url;
    }

    /**
     * @inheritdoc
     * @return static
     */
    public function withoutPath():UrlInterface
    {
        $url = clone $this;
        $url->setPath(null);
        return $url;
    }

    /**
     * @inheritdoc
     * @return string|null
     */
    public function getFragment():?string
    {
        return $this->fragment;
    }

    /**
     * Sets the URL fragment.
     *
     * @param string|null $fragment
     */
    protected function setFragment(?string $fragment):void
    {
        $this->fragment = $fragment;
    }

    /**
     * @inheritdoc
     * @param string $fragment
     * @return static
     */
    public function withFragment($fragment):UrlInterface
    {
        $url = clone $this;
        $url->setFragment($fragment);
        return $url;
    }

    /**
     * @inheritdoc
     * @return static
     */
    public function withoutFragment():UrlInterface
    {
        $url = clone $this;
        $url->setFragment(null);
        return $url;
    }

    /**
     * @inheritdoc
     * @param string $paramsSeparator
     * @return string
     */
    public function getQuery(string $paramsSeparator = '&'):string
    {
        $queryString = '';
        foreach ($this->query as $param => $value) {
            if (!empty($queryString)) {
                $queryString .= $paramsSeparator;
            }
            $queryString .= urlencode($param);
            if (!empty($value) || (string)$value === '0') {
                $queryString .= "=".urlencode($value);
            }
        }
        return $queryString;
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function getQueryAsArray():array
    {
        return $this->query;
    }

    /**
     * Sets the query string
     *
     * @param string|iterable|null $query
     */
    protected function setQuery($query):void
    {
        if (!empty($query)) {
            if (is_string($query)) {
                parse_str($query, $this->query);
            }
            elseif (is_iterable($query)) {
                foreach ($query as $param => $value) {
                    $this->query[(string)$param] = (string)$value;
                }
            }
        }
        else {
            $this->query = [];
        }
    }

    /**
     * @inheritdoc
     * @param string|iterable|null $query
     * @return static
     */
    public function withQuery($query):UrlInterface
    {
        $url = clone $this;
        $url->setQuery($query);
        return $url;
    }

    /**
     * @inheritdoc
     * @return static
     */
    public function withoutQuery():UrlInterface
    {
        $url = clone $this;
        $url->setQuery(null);
        return $url;
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function getAuthority():string
    {
        $authority = '';
        if ($this->userInfo) {
            $authority = "$this->userInfo@";
        }
        if ($this->host) {
            $authority .= $this->host;
        }
        if ($this->port) {
            $authority .= ":$this->port";
        }
        return $authority;
    }

    /**
     * @inheritdoc
     * @param bool $withHost
     * @param bool $withUserInfo
     * @param bool $withPort
     * @param bool $withQuery
     * @param bool $withFragment
     * @param string $queryParamsSeparator
     * @return string
     */
    public function buildUrl(bool $withHost = true, bool $withUserInfo = true, bool $withPort = true,
        bool $withQuery = true, bool $withFragment = true, string $queryParamsSeparator = '&'):string
    {
        $url = "";

        if ($withHost && $this->host) {
            $scheme = $this->scheme ?? 'http';
            $url .= "$scheme://";

            // user + pass
            if ($withUserInfo && $this->userInfo) {
                $url .= "$this->userInfo@";
            }

            // host
            $url .= $this->host;

            // port
            if ($withPort && $this->port)
            {
                $url .= ":$this->port";
            }

        }

        // path
        $url .= $this->path ?: "/";

        // query
        if ($withQuery && $this->query) {
            $url .= "?{$this->getQuery($queryParamsSeparator)}";
        }

        // fragment
        if ($withFragment && $this->fragment) {
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
        try {
            return $this->buildUrl();
        } catch (\Throwable $exception) {
            return sprintf("Error [%s]: %s", get_class($exception), $exception->getMessage());
        }
    }
}