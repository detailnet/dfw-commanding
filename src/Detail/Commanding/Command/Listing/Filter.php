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

        if ($operator !== null) {
            $this->setOperator($operator);
        }

        if ($type !== null) {
            $this->setType($type);
        }

        // Has to be done after the type has been set (if any)
        $this->setValue($value);
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
        $this->value = $this->castToType($value);
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

    /**
     * @param mixed $value
     * @return mixed
     */
    protected function castToType($value)
    {
        $typesMapping = array(
            'boolean' => array('bool', 'boolean'),
            'integer' => array('int', 'digit', 'integer'),
            'float' => array('float', 'decimal', 'double', 'real'),
            'string' => array('str', 'string', 'uuid'),
            'array' => array('array', 'hash'),
        );

        foreach ($typesMapping as $typeToSet => $mapping) {
            if (in_array($this->getType(), $mapping)) {
                settype($value, $typeToSet);
            }
        }

        return $value;
    }
}
