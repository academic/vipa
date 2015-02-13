<?php
/**
 * Date: 13.11.14
 * Time: 14:07
 * Devs: [
 *   ]
 */

namespace Ojs\SearchBundle\Manager;


use Elastica\Query;
use Elastica\Query\Bool;
use Elastica\Query\MultiMatch;
use Elastica\Request;
use Elastica\Result;
use FOS\ElasticaBundle\Doctrine\ORM\ElasticaToModelTransformer;
use FOS\ElasticaBundle\Persister\ObjectPersister;
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

    public function tagSearch()
    {
        $search = $this->container->get('fos_elastica.index.search');
        $query = new Query\Bool();
        $must = new Query\Match();
        $must->setField('tags',$this->getParam('term'));
        $query->addMust($must);
        $return_data = [];
        $results = $search->search($query);
        $count = 0;
        foreach ($results as $result) {
            /** @var Result $result $x */
            if(!isset($return_data[$result->getType()]))
                $return_data[$result->getType()]=['type','data'];
            $return_data[$result->getType()]['type']=$this->getTypeText($result->getType());
            if(isset($return_data[$result->getType()]['data'])):
                $return_data[$result->getType()]['data'][]= $this->getObject($result);
            else:
                $return_data[$result->getType()]['data']= [$this->getObject($result)];
            endif;

            $count=$count+count($result->getData());
        }
        $this->setCount($count);
        return $return_data;
    }

    public function getObject(Result $result)
    {
        $data = $this->container->get('fos_elastica.index.search');
        $mapping = $data->getMapping();
        $model = $mapping[$result->getType()]['_meta']['model'];
        $qb= $this->container->get('doctrine.orm.entity_manager')->createQueryBuilder();
        $data = $qb->from($model,'d')
            ->select('d')
            ->where($qb->expr()->eq('d.id',':id'))
            ->setParameter('id',$result->getId())
        ;
        $cache = $data->getQuery()->getQueryCacheDriver();
        if(!$cache->contains($result->getId()."-".$model))
            $cache->save($result->getId()."-".$model,$data->getQuery()->getOneOrNullResult());
        return $cache->fetch($result->getId()."-".$model);
    }
    public function getTypeText($type){
        $translator = $this->container->get('translator');
        return $translator->trans($type);
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