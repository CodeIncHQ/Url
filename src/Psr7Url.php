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
// Date:     22/02/2018
// Time:     23:22
// Project:  Url
//
declare(strict_types = 1);
namespace CodeInc\Url;
use Psr\Http\Message\UriInterface;


/**
 * Class Psr7Url
 *
 * @package CodeInc\Url\Psr7
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class Psr7Url extends ImmutableUrl implements UriInterface {
	/**
	 * @inheritdoc
	 * @return null|string
	 */
	public function getUserInfo():?string
	{
		if ($user = $this->getUser()) {
			$userInfo = urlencode($user);
			if ($password = $this->getPassword()) {
				$userInfo .= ":".urlencode($password);
			}
			return $userInfo;
		}
		return null;
	}

	/**
	 * Compatibility with UriInteface
	 *
	 * @inheritdoc
	 * @param string $scheme
	 */
	public function withScheme($scheme)
	{
		return parent::withScheme((string)$scheme);
	}

	/**
	 * Compatibility with UriInteface
	 *
	 * @inheritdoc
	 * @param string $host
	 */
	public function withHost($host)
	{
		return parent::withHost((string)$host);
	}

	/**
	 * Compatibility with UriInteface
	 *
	 * @inheritdoc
	 * @param int $port
	 */
	public function withPort($port)
	{
		return parent::withPort((int)$port);
	}

	/**
	 * Compatibility with UriInteface
	 *
	 * @inheritdoc
	 * @param string $path
	 */
	public function withPath($path)
	{
		return parent::withPath((string)$path);
	}

	/**
	 * Compatibility with UriInteface
	 *
	 * @inheritdoc
	 * @param string $fragment
	 */
	public function withFragment($fragment)
	{
		return parent::withFragment((string)$fragment);
	}

	/**
	 * Compatibility with UriInteface
	 *
	 * @inheritdoc
	 * @see UriInterface::withQuery()
	 * @see ImmutableUrl::withQueryString()
	 * @param string $host
	 */
	public function withQuery($parameters)
	{
		return parent::withQueryString((string)$parameters);
	}

	/**
	 * @inheritdoc
	 * @return null|string
	 */
	public function getAuthority():?string
	{
		if ($host = $this->getHost()) {
			$authority = null;
			if ($userInfo = $this->getUserInfo()) {
				$authority = "$userInfo@";
			}
			$authority .= $host;
			if ($port = $this->getPort()) {
				$authority .= ":$port";
			}
			return $authority;
		}
		return null;
	}

	/**
	 * @inheritdoc
	 * @param string $user
	 * @param null $password
	 * @return static
	 */
	public function withUserInfo($user, $password = null)
	{
		$url = clone $this;
		$url->user = (string)$user;
		$this->password = (string)$password;
		return $url;
	}
}