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
// Project:  lib-url
//
declare(strict_types = 1);
namespace CodeInc\Url;
use Psr\Http\Message\UriInterface;


/**
 * Class Psr7Url
 *
 * @package CodeInc\Url
 * @link https://www.php-fig.org/psr/psr-7/#35-psrhttpmessageuriinterface
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class Psr7Url extends Url implements UriInterface {
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
	 * @param string $fragment
	 * @return Psr7Url
	 */
	public function withFragment($fragment):Psr7Url
	{
		$url = clone $this;
		$url->setFragment((string)$fragment);
		return $url;
	}

	/**
	 * @inheritdoc
	 * @param string $host
	 * @return Psr7Url
	 */
	public function withHost($host):Psr7Url
	{
		$url = clone $this;
		$url->setHost((string)$host);
		return $url;
	}

	/**
	 * @inheritdoc
	 * @param string $path
	 * @return Psr7Url
	 */
	public function withPath($path):Psr7Url
	{
		$url = clone $this;
		$url->setPath((string)$path);
		return $url;
	}

	/**
	 * @inheritdoc
	 * @param int|null $port
	 * @return Psr7Url
	 */
	public function withPort($port):Psr7Url
	{
		$url = clone $this;
		$url->setPort((int)$port);
		return $url;
	}

	/**
	 * @inheritdoc
	 * @param string $query
	 * @return Psr7Url
	 */
	public function withQuery($query):Psr7Url
	{
		$url = clone $this;
		$url->setQuery((array)$query);
		return $url;
	}

	/**
	 * @inheritdoc
	 * @param string $scheme
	 * @return Psr7Url
	 */
	public function withScheme($scheme):Psr7Url
	{
		$url = clone $this;
		$url->setScheme((string)$scheme);
		return $url;
	}

	/**
	 * @inheritdoc
	 * @param string $user
	 * @param null $password
	 * @return Psr7Url
	 */
	public function withUserInfo($user, $password = null):Psr7Url
	{
		$url = clone $this;
		$url->setUser((string)$user);
		if ($password !== null) $this->setPassword((string)$password);
		return $url;
	}
}