<?php

namespace Detail\Commanding\Command\Listing;

class Sort
{
    const DIRECTION_ASCENDING  = 'asc';
    const DIRECTION_DESCENDING = 'desc';

    /**
     * @var string
     */
    protected $property;

    /**
     * @var string
     */
    protected $direction = self::DIRECTION_ASCENDING;

    /**
     * @param string $property
     * @param string $direction
     */
    public function __construct($property, $direction = null)
    {
        $this->setProperty($property);

        if ($direction !== null) {
            $this->setDirection($direction);
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
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param string $direction
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
    }
}
