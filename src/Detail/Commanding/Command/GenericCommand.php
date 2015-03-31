<?php

namespace Detail\Commanding\Command;

//use ArrayAccess;
use ReflectionObject;
use ReflectionProperty;
use Traversable;

use Detail\Commanding\Exception;

abstract class GenericCommand implements
    CommandInterface//,
//    ArrayAccess
{
    const CALL_GET = 'get';
    const CALL_SET = 'set';

//    /**
//     * @var array
//     */
//    protected $params = array();

    /**
     * The params the we're actually set/modified.
     *
     * @var array
     */
    private $modifiedParams = array();

    /**
     * The snake case names/keys of the accepted params.
     *
     * @var array
     */
    private $acceptedParams;

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

        // Check if the params are all accepted...
        foreach ($params as $key => $value) {
            $this->acceptsParam($key);
        }

        // ...before resetting...
        foreach ($this->getAcceptedParams() as $key) {
            $this->setParam($key, null);
        }

        $this->modifiedParams = array();

        // ...and actually setting the new values
        foreach ($params as $key => $value) {
            $this->setParam($key, $value);
        }
    }

    /**
     * @return array
     */
    public function getParams()
    {
        $params = array();

        foreach ($this->getModifiedParams() as $key) {
            $params[$key] = $this->getParam($key);
        }

        return $params;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setParam($key, $value)
    {
        $this->acceptsParam($key);

        $property = $this->getPropertyName($key);

        $this->$property = $value;
        $this->modifiedParams[] = $key;
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function getParam($key)
    {
        $this->acceptsParam($key);

        $property = $this->getPropertyName($key);

        return in_array($key, $this->modifiedParams) ? $this->$property : null;
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
                    $name
                )
            );
        }

        // Get the snake case key
        $key = $this->getParamKey($camelCaseKey);

        // Check if the commands accepts the param
        try {
            $this->acceptsParam($key);
        } catch (Exception\InvalidArgumentException $e) {
            throw new Exception\BadMethodCallException(
                sprintf(
                    'Method %s does not exist',
                    $name
                ),
                0,
                $e
            );
        }

        // Transform the key to snake case and check if the commands accepts it.
        $result = null;

        switch ($supportedMethod) {
            case self::CALL_GET:
                $result = $this->getParam($key);
                break;
            case self::CALL_SET:
                // We expect the value to be the first argument
                $this->setParam($key, current($arguments));
                break;
        }

        return $result;
    }

    /**
     * @param string $camelCaseKey
     * @return string
     */
    private function getParamKey($camelCaseKey)
    {
        $key = ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $camelCaseKey)), '_');

        return $key;
    }

    private function getPropertyName($snakeCaseKey)
    {
        $key = str_replace(' ', '', ucwords(str_replace('_', ' ', $snakeCaseKey)));

        // Keep the first char as is...
        $key = $snakeCaseKey[0] . substr($key, 1);

        return $key;
    }

    /**
     * @return array
     */
    private function getModifiedParams()
    {
        return $this->modifiedParams;
    }

    /**
     * @return array
     */
    private function getAcceptedParams()
    {
        if ($this->acceptedParams === null) {
            $this->initAcceptedParams();
        }

        return $this->acceptedParams;
    }

    /**
     * @param string $key
     * @param bool $throwExceptionWhenNotAccepted
     * @return bool
     */
    private function acceptsParam($key, $throwExceptionWhenNotAccepted = true)
    {
        if (strlen($key) === 0) {
            throw new Exception\InvalidArgumentException(
                'Invalid param key; must be a non empty string'
            );
        }

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

    /**
     * @return void
     */
    private function initAcceptedParams()
    {
        $command = new ReflectionObject($this);
        $keys = array();

        // Note that we don't care about the visibility of a property (public, protected or private)
        foreach ($command->getProperties() as $property) {
            // Ignore properties starting with "__" because they're usually internal properties
            // we're not interested in...
            if (strpos($property->getName(), '__') === 0) {
                continue;
            }

            $keys[] = $this->getParamKey($property->getName());
        }

        $this->acceptedParams = $keys;
    }
}
