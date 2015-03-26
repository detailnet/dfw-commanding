<?php

namespace Detail\Commanding\Command;

//use ArrayAccess;
use Traversable;

use Detail\Commanding\Exception;

abstract class GenericCommand implements
    CommandInterface//,
//    ArrayAccess
{
    const CALL_GET = 'get';
    const CALL_SET = 'set';

    /**
     * @var array
     */
    protected $params = array();

    /**
     * @var array
     */
    protected $acceptedParams = array();

    /**
     * @param array|Traversable|null $params
     */
    public function __construct($params = null)
    {
        if ($params !== null) {
            $this->setParams($params);
        }
    }

    /**
     * @param array|Traversable|null $params
     */
    public function setParams($params)
    {
        if (!is_array($params) && !$params instanceof Traversable) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    'Parameter provided to %s must be an array or Traversable object',
                    __METHOD__
                )
            );
        }

        // Check if the params are all accepted before setting
        foreach ($params as $key => $value) {
            $this->acceptsParam($key);
        }

        // Replace all params
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array|Traversable|null $params
     */
    public function setFromArray($params)
    {
        $this->setParams($params);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->getParams();
    }

//    public function offsetExists($offset)
//    {
//    }
//
//    public function offsetGet($offset)
//    {
//    }
//
//    public function offsetSet($offset, $value)
//    {
//    }
//
//    public function offsetUnset($offset)
//    {
//    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, array $arguments)
    {
        // We expect the name to be prefixed by either "get" or "set",
        // followed by a camel case param name.
        // Examples (method => param key):
        // - getName => name
        // - setProductGroups => product_groups

        $supportedMethods = array(self::CALL_GET, self::CALL_SET);
        $supportedMethod = null;
        $camelCaseKey = null;

        foreach ($supportedMethods as $supportedMethod) {
            if (strpos($name, $supportedMethod) === 0) {
                $camelCaseKey = substr($name, strlen($supportedMethod));
                break;
            }
        }

        if ($camelCaseKey === null) {
            throw new Exception\BadMethodCallException(
                sprintf(
                    'Method %s does not exist',
                    __METHOD__
                )
            );
        }

        // Transform the key to snake case and check if the commands accepts it.
        $key = $this->getParamKey($camelCaseKey);
        $result = null;

        switch ($supportedMethod) {
            case self::CALL_GET:
                $result = $this->getParam($key);
                break;
            case self::CALL_SET:
                // We expect the value to be the first argument
                $this->params[$key] = current($arguments);
                break;
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function getAcceptedParams()
    {
        return $this->acceptedParams;
    }

    /**
     * @param string $camelCaseKey
     * @return string
     */
    protected function getParamKey($camelCaseKey)
    {
        $key = ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $camelCaseKey)), '_');
        $this->acceptsParam($key);

        return $key;
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    protected function getParam($key)
    {
        return array_key_exists($key, $this->params) ? $this->params[$key] : null;
    }

    /**
     * @param string $key
     * @param bool $throwExceptionWhenNotAccepted
     * @return bool
     */
    protected function acceptsParam($key, $throwExceptionWhenNotAccepted = true)
    {
        $acceptsParam = in_array($key, $this->getAcceptedParams());

        if ($acceptsParam !== true && $throwExceptionWhenNotAccepted !== false) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    'Command %s does not accept param "%s"',
                    get_class($this),
                    $key
                )
            );
        }

        return $acceptsParam;
    }
}
