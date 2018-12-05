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
// | reproduction of this material is strictly forbidden unless prior    |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     2018-12-05
// Project:  Url
//
declare(strict_types=1);
namespace CodeInc\Url;

/**
 * Class Parameters
 *
 * @package CodeInc\Url
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface ParametersInterface extends \IteratorAggregate, \Countable, \ArrayAccess
{
    /**
     * Verifies if a parameter is set.
     *
     * @param string $param
     * @return bool
     */
    public function hasParam(string $param):bool;

    /**
     * Removes a parameter.
     *
     * @param string $param
     * @return ParametersInterface
     */
    public function withoutParam(string $param):self;

    /**
     * Returns the value of a parameter or NULL if not set.
     *
     * @param string $param
     * @return mixed|null
     */
    public function getValue(string $param);

    /**
     * Sets the vlaue of a parameter.
     *
     * @param string $param
     * @param mixed|null $value
     * @return ParametersInterface
     */
    public function withParam(string $param, $value = null):self;

    /**
     * Sets the values of multiple parameters using an iterable (keys are parameters names).
     *
     * @param iterable $params
     * @return ParametersInterface
     */
    public function withParams(iterable $params):self;

    /**
     * Returns all the parameters in an array.
     *
     * @return array
     */
    public function getParams():array;

    /**
     * Returns all the parameters as a string
     *
     * @param string $paramSeparator
     * @return string
     */
    public function getParamsAsString(string $paramSeparator = '&'):string;
}