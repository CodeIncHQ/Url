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
// Project:  Url
//
declare(strict_types=1);
namespace CodeInc\Url\Tests;
use CodeInc\Url\Url;
use PHPUnit\Framework\TestCase;


/**
 * Class UrlTest
 *
 * @uses Url
 * @package Tests\CodeInc\Url
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class UrlTest extends TestCase
{
	// test params
	private const TEST_SCHEME = "https";
	private const TEST_USER = "user";
	private const TEST_PASSWORD = "pass";
	private const TEST_HOST = "www.example.com";
	private const TEST_PORT = 8080;
	private const TEST_PATH = "/a/great_path";
	private const TEST_QUERY = "p1=val1&p2&p3=1&p4=0";
	private const TEST_FRAGMENT = "fragment";

	// test URL
    private const TEST_FULL_URL = self::TEST_SCHEME."://".self::TEST_USER.":".self::TEST_PASSWORD."@".self::TEST_HOST
    .":".self::TEST_PORT.self::TEST_PATH."?".self::TEST_QUERY."#".self::TEST_FRAGMENT;
    private const TEST_REL_URL = self::TEST_PATH."?".self::TEST_QUERY."#".self::TEST_FRAGMENT;

    /**
     * Tests the URL builder.
     */
    public function testUrlBuilder():void
    {
        $url = (new Url())
            ->withScheme(self::TEST_SCHEME)
            ->withUserInfo(self::TEST_USER, self::TEST_PASSWORD)
            ->withHost(self::TEST_HOST)
            ->withPort(self::TEST_PORT)
            ->withPath(self::TEST_PATH)
            ->withQuery(self::TEST_QUERY)
            ->withFragment(self::TEST_FRAGMENT);
        $this->assertSame(self::TEST_FULL_URL, $url->getFullUrl());
        $this->assertSame(self::TEST_REL_URL, $url->getRelUrl());
    }

    /**
     * Tests the URL query.
     */
    public function testUrlQuery():void
    {
        $url = Url::fromString(self::TEST_FULL_URL)
            ->withQuery(['p4' => 0])
            ->withQuery(['p2' => null]);
        $this->assertSame(self::TEST_FULL_URL, $url->getFullUrl());
        $this->assertSame(self::TEST_REL_URL, $url->getRelUrl());
    }

	/**
	 * Tests the URL parser.
	 */
	public function testUrlParser():void
    {
		$url = Url::fromString(self::TEST_FULL_URL);
		$this->assertSame(self::TEST_SCHEME, $url->getScheme());
		$this->assertSame(self::TEST_USER, $url->getUser());
		$this->assertSame(self::TEST_PASSWORD, $url->getPassword());
		$this->assertSame(self::TEST_HOST, $url->getHost());
		$this->assertSame(self::TEST_PORT, $url->getPort());
		$this->assertSame(self::TEST_PATH, $url->getPath());
		$this->assertSame(self::TEST_QUERY, $url->getQuery());
		$this->assertSame(self::TEST_FRAGMENT, $url->getFragment());
	}
}