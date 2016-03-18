<?php

namespace Detail\Commanding\Command\Listing;

use Detail\Commanding\Exception;

class Filter
{
    const OPERATOR_SMALLER_THAN           = '<';
    const OPERATOR_SMALLER_THAN_OR_EQUALS = '<=';
    const OPERATOR_EQUALS                 = '=';
    const OPERATOR_GREATER_THAN_OR_EQUALS = '>=';
    const OPERATOR_GREATER_THAN           = '>';
    const OPERATOR_NOT_EQUALS             = '!=';
    const OPERATOR_IN                     = 'in';
    const OPERATOR_NOT_IN                 = 'notIn';
    const OPERATOR_LIKE                   = 'like';

    /**
     * @var string
     */
    protected $property;

    /**
     * @var string
     */
    protected $operator = self::OPERATOR_EQUALS;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * @return array
     */
    protected static function getSupportedTypes()
    {
        return array(
            'boolean' => array('bool', 'boolean'),
            'integer' => array('int', 'digit', 'integer'),
            'float' => array('float', 'decimal', 'double', 'real'),
            'string' => array('str', 'string', 'uuid'),
            'array' => array('array', 'hash'),
        );
    }

    /**
     * @param string $property
     * @param mixed $value
     * @param string|null $operator
     * @param string|null $type
     */
    public function __construct($property, $value, $operator = null, $type = null)
    {
        $this->setProperty($property);
        $this->setValue($value, $type);

        if ($operator !== null) {
            $this->setOperator($operator);
        }
    }

    /**
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param string $property
     */
    public function setProperty($property)
    {
        $this->property = $property;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @param string|null $type
     */
    public function setValue($value, $type = null)
    {
        if ($type !== null) {
            $this->setType($type);
        }

        // If type is set cast value to it
        if ($this->getType() !== null) {
            // No need to check that getMainType result is not null
            settype($value, $this->getMainType($this->getType()));
        }

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $type
     * @return string|null
     */
    protected function getMainType($type)
    {
        foreach (static::getSupportedTypes() as $mainType => $mapping) {
            if (in_array($type, $mapping)) {
                return $mainType;
            }
        }

        return null;
    }

    /**
     * @param string $type
     */
    private function setType($type)
    {
        if ($this->getMainType($type) === null) {
            throw new Exception\InvalidArgumentException(
                sprintf('Unsupported type "%s"', $type)
            );
        }

        $this->type = $type;
    }
}
