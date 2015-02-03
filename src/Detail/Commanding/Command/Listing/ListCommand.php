<?php

namespace Detail\Commanding\Command\Listing;

use Detail\Commanding\Command\CommandInterface;

abstract class ListCommand implements
    CommandInterface
{
    /**
     * @var string
     */
    protected $query;

    /**
     * @var Filter[]
     */
    protected $filter = array();

    /**
     * @var Sort[]
     */
    protected $sort = array();

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @param array $params
     * @return static
     */
    public static function fromArray(array $params)
    {
        return new static(
            isset($params['query']) ? $params['query'] : null,
            isset($params['filter']) ? $params['filter'] : array(),
            isset($params['sort']) ? $params['sort'] : array(),
            isset($params['limit']) ? $params['limit'] : null,
            isset($params['offset']) ? $params['offset'] : null
        );
    }

    /**
     * @param string $query
     * @param Filter[] $filter
     * @param Sort[] $sort
     * @param int $limit
     * @param int $offset
     */
    public function __construct($query = null, array $filter = array(), array $sort = array(), $limit = null, $offset = null)
    {
        if ($query !== null) {
            $this->setQuery($query);
        }

        $this->setFilter($filter);
        $this->setSort($sort);

        if ($limit !== null) {
            $this->setLimit($limit);
        }

        if ($offset !== null) {
            $this->setOffset($offset);
        }
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param string $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * @return Filter[]
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param Filter[] $filter
     */
    public function setFilter(array $filter)
    {
        /** @todo Assert Filter objects */
        $this->filter = $filter;
    }

    /**
     * @return Sort[]
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param Sort[] $sort
     */
    public function setSort(array $sort)
    {
        /** @todo Assert Sort objects */
        $this->sort = $sort;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }
}
