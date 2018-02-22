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
// Time:     23:23
// Project:  lib-url
//
declare(strict_types = 1);
namespace CodeInc\Url;


/**
 * Class ServerUrl
 *
 * @package CodeInc\Url
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class ServerUrl extends Url {
	/**
	 * ServerUrl constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->useCurrentScheme();
		$this->useCurrentHost();
		$this->useCurrentPath();
		$this->useCurrentQuery();
		$this->useCurrentUser();
		$this->useCurrentPassword();
	}
}