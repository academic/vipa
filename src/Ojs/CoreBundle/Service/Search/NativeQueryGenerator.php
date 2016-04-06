<?php

namespace Ojs\CoreBundle\Service\Search;

/**
 * Class $this
 * @package Ojs\CoreBundle\Service
 */
class NativeQueryGenerator
{
    private $query = null;

    private $requestAggsBag = [];

    public function generateNativeQuery($query, $aggsBag)
    {

    }

    /**
     * @return null
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param null $query
     * @return $this
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @return array
     */
    public function getRequestAggsBag()
    {
        return $this->requestAggsBag;
    }

    /**
     * @param array $requestAggsBag
     * @return $this
     */
    public function setRequestAggsBag($requestAggsBag)
    {
        $this->requestAggsBag = $requestAggsBag;

        return $this;
    }
}
