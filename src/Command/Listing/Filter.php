<?php

namespace Detail\Commanding\Command\Listing;

use DateTime;
use DateTimeInterface;

use Detail\Commanding\Exception;

class Filter
{
    const OPERATOR_SMALLER_THAN = '<';
    const OPERATOR_SMALLER_THAN_OR_EQUALS = '<=';
    const OPERATOR_EQUALS = '=';
    const OPERATOR_GREATER_THAN_OR_EQUALS = '>=';
    const OPERATOR_GREATER_THAN = '>';
    const OPERATOR_NOT_EQUALS = '!=';
    const OPERATOR_IN = 'in';
    const OPERATOR_NOT_IN = 'notIn';
    const OPERATOR_LIKE = 'like';

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
        return [
            'boolean' => ['bool', 'boolean'],
            'integer' => ['int', 'digit', 'integer'],
            'float' => ['float', 'decimal', 'double', 'real'],
            'string' => ['str', 'string', 'uuid'],
            'array' => ['array', 'hash'],
            'date' => ['date', 'datetime'],
        ];
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
            $this->setType($type, false);
        } else {
            $type = $this->getType();
        }

        // If type is set cast value to it
        if ($type !== null) {
            // No need to check that getMainType result is not null
            $mainType = $this->getMainType($this->getType());

            if ($mainType == 'date') {
                if (!$value instanceof DateTimeInterface) {
                    $value = new DateTime($value);
                }
            } else {
                // For scalars
                settype($value, $this->getMainType($this->getType()));
            }
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
     * @param string|null $type
     * @param boolean $castValue
     */
    public function setType($type, $castValue = true)
    {
        if ($this->getMainType($type) === null) {
            throw new Exception\InvalidArgumentException(
                sprintf('Unsupported type "%s"', $type)
            );
        }

        if ($castValue !== false) {
            $this->setValue($this->getValue(), $type);
        } else {
            $this->type = $type;
        }
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
}
