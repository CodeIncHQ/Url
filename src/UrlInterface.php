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
use Psr\Http\Message\UriInterface;


/**
 * Interface UrlInterface
 *
 * @package CodeInc\Url
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface UrlInterface extends UriInterface
{
	/**
	 * Returns the URL scheme.
	 *
	 * @return null|string
	 */
	public function getScheme():?string;

    /**
     * Returns the URL without a scheme.
     *
     * @return UrlInterface
     */
    public function withoutScheme():self;

    /**
     * @inheritdoc
     * @param string $scheme
     * @return UrlInterface
     */
    public function withScheme($scheme):self;

	/**
	 * Returns the host name or IP address or null if not set.
	 *
	 * @return null|string
	 */
	public function getHost():?string;

    /**
     * @inheritdoc
     * @return UrlInterface
     */
    public function withHost($host):self;

    /**
     * Returns the URL without the host.
     *
     * @return static
     */
    public function withoutHost():self;

	/**
	 * Returns the host port number.
	 *
	 * @return int|null
	 */
	public function getPort():?int;

    /**
     * @inheritdoc
     * @return UrlInterface
     */
    public function withPort($port):self;

    /**
     * Returns the URL without a port.
     *
     * @return UrlInterface
     */
    public function withoutPort():self;

	/**
     * @inheritdoc
	 * @return null|string
	 */
	public function getUserInfo():?string;

    /**
     * Returns the user name or NULL if not set.
     *
     * @return string|null
     */
    public function getUser():?string;

    /**
     * Returns the user password or NULL if not set.
     *
     * @return string|null
     */
    public function getPassword():?string;

    /**
     * @inheritdoc
     * @return UrlInterface
     */
    public function withUserInfo($user, $password = null):self;

    /**
     * Returns the URL without user and password.
     *
     * @return UrlInterface
     */
    public function withoutUserInfo():self;

	/**
	 * Returns the path or null if not set.
	 *
	 * @return null|string
	 */
	public function getPath():?string;

    /**
     * @inheritdoc
     * @return UrlInterface
     */
    public function withPath($path):self;

    /**
     * Returns the URL without a path.
     *
     * @return UrlInterface
     */
    public function withoutPath():self;

    /**
	 * Returns the URL fragment or null if not set.
	 *
	 * @return null|string
	 */
	public function getFragment():?string;

    /**
     * @inheritdoc
     * @param string $fragment
     * @return static
     */
    public function withFragment($fragment):self;

    /**
     * Returns the URL without a fragment.
     *
     * @return static
     */
    public function withoutFragment():self;

    /**
     * @inheritdoc
     * @param string $paramsSeparator
     * @return string
     */
    public function getQuery(string $paramsSeparator = '&'):string;

    /**
     * Returns the query parameters in an array.
     *
     * @return array
     */
    public function getQueryAsArray():array;

    /**
     * @inheritdoc
     * @param string|iterable|null $query
     * @return static
     */
    public function withQuery($query):UrlInterface;

    /**
     * Returns the URL without a query string.
     *
     * @return static
     */
    public function withoutQuery():UrlInterface;

    /**
     * @inheritdoc
     */
    public function getAuthority():string;

	/**
	 * Builds a custom URL.
	 *
	 * @param bool $withHost Includes the hostname (default: true)
	 * @param bool $withUser Includes the user and password (defaut: true)
	 * @param bool $withPort Includes the port number (default: true)
	 * @param bool $withQuery Incldues the query string (default: true)
	 * @param bool $withFragment Includes the fragment (default: true)
	 * @return string
	 */
	public function buildUrl(bool $withHost = true, bool $withUser = true, bool $withPort = true,
        bool $withQuery = true, bool $withFragment = true):string;
}