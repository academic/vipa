<?php
/**
 * User: aybarscengaver
 * Date: 13.11.14
 * Time: 14:07
 * URI: www.emre.xyz
 * Devs: [
 * 'Aybars Cengaver'=>'aybarscengaver@yahoo.com',
 *   ]
 */

namespace Ojs\SearchBundle\Manager;


use Elastica\Query;
use Elastica\Query\Bool;
use Elastica\Query\MultiMatch;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SearchManager
 * @package Ojs\SearchBundle\Manager
 */
class SearchManager
{

    /** @var ContainerInterface */
    protected $container;
    private $term;

    private $finder;
    private $search;

    private $page = 0, $limit = 12, $result = null, $count = 0;

    private $param = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->finder = $this->search = new \stdClass();

    }

    public function search()
    {
        $finder = $this->container->get('fos_elastica.finder.search.articles');
        $search = $this->container->get('fos_elastica.index.search.articles');

        $bool = new Bool();
        $multiMatch = new MultiMatch();
        $multiMatch->setFields(['subjects', 'title', 'keywords', 'subtitle', 'citations.raw', 'journal.title', 'journal.subtitle']);
        $multiMatch->setType('phrase_prefix');
        $multiMatch->setQuery($this->getParam('term'));
        $bool->addMust($multiMatch);

        $query = new Query();
        $query->setQuery($bool);
        $query->setFrom($this->getPage() * $this->getLimit());
        $query->setSize($this->getLimit());
        $this->result = $finder->find($query);

        $this->setCount($search->count($query));

        return $this;
    }

    public function getPageCount()
    {
        return ceil($this->getCount() / $this->getLimit());
    }

    /**
     * @param $term
     * @return SearchManager
     */
    public function setTerm($term)
    {
        $this->term = $term;
        return $this;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page - 1 ;
    }

    /**
     * @param int $page
     * @return SearchManager
     */
    public function setPage($page)
    {
        $this->page = $page;
        $page < 1 && ($this->page = 1);
        return $this;

    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     * @return SearchManager
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return SearchManager
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTerm()
    {
        return $this->term;
    }


    /**
     * @param string $key
     * @return array
     */
    public function getParam($key = null)
    {
        if ($key)
            return $this->param[$key];
        return $this->param;
    }

    /**
     * @param array $param
     * @return SearchManager
     */
    public function setParam($param)
    {
        $this->param = $param;
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return SearchManager
     */
    public function addParam($key, $value)
    {
        $this->param[$key] = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $count
     * @return SearchManager
     */
    public function setCount($count)
    {
        $this->count = $count;
        return $this;
    }

}