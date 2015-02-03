<?php

namespace Detail\Commanding\Command\Listing;

class Filter
{
    const OPERATOR_SMALLER_THAN           = '<';
    const OPERATOR_SMALLER_THAN_OR_EQUALS = '<=';
    const OPERATOR_EQUALS                 = '=';
    const OPERATOR_GREATER_THAN_OR_EQUALS = '>=';
    const OPERATOR_GREATER_THAN           = '>';
    const OPERATOR_NOT_EQUALS             = '!=';
    const OPERATOR_IN                     = 'in';
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
     * @var string
     */
    protected $type = 'string';

    /**
     * @param string $property
     * @param mixed $value
     * @param string $operator
     * @param string $type
     */
    public function __construct($property, $value, $operator = null, $type = null)
    {
        $this->setProperty($property);
        $this->setValue($value);

        if ($operator !== null) {
            $this->setOperator($operator);
        }

        if ($type !== null) {
            $this->setType($type);
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
     */
    public function setValue($value)
    {
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
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}
