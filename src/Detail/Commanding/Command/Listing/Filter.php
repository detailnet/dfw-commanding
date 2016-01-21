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
     * @param string $operator
     * @param string $type
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
     * @param string $type
     */
    public function setValue($value, $type = null)
    {
        if ($type !== null) {
            $this->setType($type);
        }

        // Has to be done after the type has been set (if any)
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
     * @param mixed $value
     * @return mixed
     */
    protected function castToType($value)
    {
        foreach (static::getSupportedTypes() as $typeToSet => $mapping) {
            if (in_array($this->getType(), $mapping)) {
                settype($value, $typeToSet);
            }
        }

        return $value;
    }

    /**
     * @param string $type
     */
    private function setType($type)
    {
        /** @todo Check that is one of the supported types first? */
        $this->type = $type;
    }
}
