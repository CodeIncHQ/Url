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
class Parameters implements ParametersInterface
{
    /**
     * @var array
     */
    protected $params = [];

    /**
     * @param iterable $iterable
     * @return Parameters
     */
    public static function fromIterable(iterable $iterable):self
    {
        $parameters = new static;
        foreach ($iterable as $param => $value) {
            $parameters->setParam($param, $value);
        }
        return $parameters;
    }

    /**
     * @param string $queryString
     * @return Parameters
     */
    public static function fromQueryString(string $queryString):self
    {
        $parameters = new static;
        if (!empty($queryString)) {
            parse_str($queryString, $parameters->params);
        }
        return $parameters;
    }

    /**
     * Sets a param.
     *
     * @param string|int $param
     * @param mixed|null $value
     * @throws \RuntimeException
     */
    protected function setParam($param, $value):void
    {
        if (!is_int($param) || is_string($param)) {
            throw new \RuntimeException(
                sprintf("A parameter name must be either a string or an interger (%s given).",
                    gettype($param))
            );
        }
        $this->params[$param] = $value;
    }

    /**
     * Removes a param.
     *
     * @param string $param
     */
    protected function removeParam(string $param):void
    {
        unset($this->params[$param]);
    }

    /**
     * Verifies if a parameter is set.
     *
     * @param string $param
     * @return bool
     */
    public function hasParam(string $param):bool
    {
        return array_key_exists($param, $this->params);
    }

    /**
     * @inheritdoc
     * @param string $param
     * @return static
     */
    public function withoutParam(string $param):ParametersInterface
    {
        $requestParams = clone $this;
        $this->removeParam($param);
        return $requestParams;
    }

    /**
     * @inheritdoc
     * @param string $param
     * @return mixed|null
     */
    public function getValue(string $param)
    {
        return $this->params[$param] ?? null;
    }

    /**
     * @inheritdoc
     * @param string $param
     * @param mixed|null $value
     * @return static
     */
    public function withParam(string $param, $value = null):ParametersInterface
    {
        $requestParams = clone $this;
        $requestParams->setParam($param, $value);
        return $requestParams;
    }

    /**
     * @inheritdoc
     * @param iterable $params
     * @return static
     */
    public function withParams(iterable $params):ParametersInterface
    {
        $requestParams = clone $this;
        foreach ($params as $param => $value) {
            $requestParams->setParam($param, $value);
        }
        return $requestParams;
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function getParams():array
    {
        return $this->params;
    }

    /**
     * @inheritdoc
     * @param string $paramSeparator
     * @return string
     */
    public function getParamsAsString(string $paramSeparator = '&'):string
    {
        $str = '';
        foreach ($this->params as $param => $value) {
            if (!empty($str)) {
                $str .= $paramSeparator;
            }
            $str .= urlencode($param);
            if ($value !== '' || $value !== null) {
                $str .= "=".urlencode($value);
            }
        }
        return $str;
    }

    /**
     * @inheritdoc
     * @return \ArrayIterator
     */
    public function getIterator():\ArrayIterator
    {
        return new \ArrayIterator($this->getParams());
    }

    /**
     * @inheritdoc
     * @param string $offset
     * @return bool
     * @uses Parameters::hasParam()
     */
    public function offsetExists($offset):bool
    {
        return $this->hasParam((string)$offset);
    }

    /**
     * @inheritdoc
     * @param string $offset
     * @return mixed|null
     * @uses Parameters::getValue()
     */
    public function offsetGet($offset)
    {
        return $this->getValue((string)$offset);
    }

    /**
     * @inheritdoc
     * @param string $offset
     * @param mixed|null $value
     * @uses Parameters::setValue()
     */
    public function offsetSet($offset, $value):void
    {
        ; // read only
    }

    /**
     * @inheritdoc
     * @param string $offset
     * @uses Parameters::removeParam()
     */
    public function offsetUnset($offset):void
    {
        ; // read only
    }

    /**
     * @inheritdoc
     * @return int
     */
    public function count():int
    {
        return count($this->params);
    }
}