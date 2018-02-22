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
// Date:     19/02/2018
// Time:     12:46
// Project:  lib-url
//
declare(strict_types=1);
namespace CodeInc\Url\Tests;
use CodeInc\Url\Url;
use PHPUnit\Framework\TestCase;

/**
 * Class UrlTest
 *
 * @package Tests\CodeInc\Url
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class UrlTest extends TestCase {
	// test params
	private const TEST_SCHEME = "https";
	private const TEST_USER = "user";
	private const TEST_PASSWORD = "pass";
	private const TEST_HOST = "www.example.com";
	private const TEST_PORT = 8080;
	private const TEST_PATH = "/a/great_path";
	private const TEST_QUERY = "p1=val1&p2&p3=1";
	private const TEST_FRAGMENT = "fragment";

	// test URL
	private const TEST_URL = self::TEST_SCHEME."://".self::TEST_USER.":".self::TEST_PASSWORD."@".self::TEST_HOST
		.":".self::TEST_PORT.self::TEST_PATH."?".self::TEST_QUERY."#".self::TEST_FRAGMENT;

	/**
	 * Tests the URL builder.
	 */
	public function testUrlBuilder():void {
		$url = new Url();
		$url->setScheme(self::TEST_SCHEME);
		$url->setUser(self::TEST_USER);
		$url->setPassword(self::TEST_PASSWORD);
		$url->setHost(self::TEST_HOST);
		$url->setPort(self::TEST_PORT);
		$url->setPath(self::TEST_PATH);
		$url->setQueryString(self::TEST_QUERY);
		$url->setFragment(self::TEST_FRAGMENT);
		$this->assertSame(self::TEST_URL, $url->getUrl());
	}

	/**
	 * Tests the URL parser.
	 */
	public function testUrlParser():void {
		$url = new Url(self::TEST_URL);
		$this->assertSame(self::TEST_SCHEME, $url->getScheme());
		$this->assertSame(self::TEST_USER, $url->getUser());
		$this->assertSame(self::TEST_PASSWORD, $url->getPassword());
		$this->assertSame(self::TEST_HOST, $url->getHost());
		$this->assertSame(self::TEST_PORT, $url->getPort());
		$this->assertSame(self::TEST_PATH, $url->getPath());
		$this->assertSame(self::TEST_QUERY, $url->getQueryString());
		$this->assertSame(self::TEST_FRAGMENT, $url->getFragment());
	}
}