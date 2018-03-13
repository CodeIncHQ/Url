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
// Time:     16:30
// Project:  Url
//
declare(strict_types = 1);
namespace CodeInc\Url;


/**
 * Class UrlGlobals
 *
 * @package CodeInc\Url
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class UrlGlobals {
	/**
	 * @return string
	 */
	public static function getCurrentScheme():string
	{
		return @$_SERVER["HTTPS"] == "on" ? "https" : "http";
	}

	/**
	 * @return null|string
	 */
	public static function getCurrentHost():?string
	{
		if (isset($_SERVER["HTTP_HOST"])) {
			if (($pos = strpos($_SERVER["HTTP_HOST"], ":")) !== false) {
				return substr($_SERVER["HTTP_HOST"], 0, $pos);
			}
			else {
				return $_SERVER["HTTP_HOST"];
			}
		}
		return null;
	}

	/**
	 * @return int|null
	 */
	public static function getCurrentPort():?int
	{
		if (isset($_SERVER["HTTP_HOST"]) && ($pos = strpos($_SERVER["HTTP_HOST"], ":")) !== false) {
			return (int)substr($_SERVER["HTTP_HOST"], $pos + 1);
		}
		return null;
	}

	/**
	 * @return null|string
	 */
	public static function getCurrentUser():?string
	{
		return $_SERVER["PHP_AUTH_USER"] ?? null;
	}

	/**
	 * @return null|string
	 */
	public static function getCurrentPassword():?string
	{
		return $_SERVER["PHP_AUTH_PW"] ?? null;
	}

	/**
	 * @return null|string
	 */
	public static function getCurrentPath():?string
	{
		if (isset($_SERVER["REQUEST_URI"])) {
			if (($pos = strpos($_SERVER["REQUEST_URI"], "?")) !== false) {
				return substr($_SERVER["REQUEST_URI"], 0, $pos);
			}
			else {
				return $_SERVER["REQUEST_URI"];
			}
		}
		return null;
	}

	/**
	 * @return null|string
	 */
	public static function getCurrentQueryString():?string
	{
		if (isset($_SERVER["REQUEST_URI"]) && ($pos = strpos($_SERVER["REQUEST_URI"], "?")) !== false) {
			return substr($_SERVER["REQUEST_URI"], $pos + 1);
		}
		return null;
	}

	/**
	 * @return array
	 */
	public static function getCurrentQuery():array {
		$query = [];
		if ($queryString = self::getCurrentQueryString()) {
			parse_str($queryString, $query);
		}
		return $query;
	}
}