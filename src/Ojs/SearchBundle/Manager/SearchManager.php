<?php
/**
 * Date: 13.11.14
 * Time: 14:07
 * Devs: [
 *   ]
 */

namespace Ojs\SearchBundle\Manager;


use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Util\Debug;
use Elastica\Aggregation\GlobalAggregation;
use Elastica\Aggregation\Terms;
use Elastica\Query;
use Elastica\Query\Bool;
use Elastica\Query\MultiMatch;
use Elastica\Result;
use FOS\ElasticaBundle\Doctrine\ORM\ElasticaToModelTransformer;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Debug\Exception\UndefinedMethodException;
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

    /** @var Array */
    private $aggregations;

    /** @var  Array */
    private $filter;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->finder = $this->search = new \stdClass();
        $this->aggregations = [];
        $this->filter = [];
    }

    public function tagSearch()
    {
        $search = $this->container->get('fos_elastica.index.search');
        $query = new Query\Bool();
        $must = new Query\Match();
        $must->setField('tags', $this->getParam('term'));
        $query->addMust($must);
        $return_data = [];
        $results = $search->search($query);
        $count = 0;
        foreach ($results as $result) {
            /** @var Result $result $x */
            if (!isset($return_data[$result->getType()]))
                $return_data[$result->getType()] = ['type', 'data'];
            $return_data[$result->getType()]['type'] = $this->getTypeText($result->getType());
            if (isset($return_data[$result->getType()]['data'])):
                $return_data[$result->getType()]['data'][] = $this->getObject($result);
            else:
                $return_data[$result->getType()]['data'] = [$this->getObject($result)];
            endif;

            $count = $count + count($result->getData());
        }
        $this->setCount($count);
        return $return_data;
    }

    public function getObject(Result $result)
    {
        $data = $this->container->get('fos_elastica.index.search');
        $mapping = $data->getMapping();
        $model = $mapping[$result->getType()]['_meta']['model'];
        $qb = $this->container->get('doctrine.orm.entity_manager')->createQueryBuilder();
        $data = $qb->from($model, 'd')
            ->select('d')
            ->where($qb->expr()->eq('d.id', ':id'))
            ->setParameter('id', $result->getId());
        $cache = $data->getQuery()->getQueryCacheDriver();
        if (!$cache->contains($result->getId() . "-" . $model))
            $cache->save($result->getId() . "-" . $model, $data->getQuery()->getOneOrNullResult());
        return $cache->fetch($result->getId() . "-" . $model);
    }

    public function getTypeText($type)
    {
        $translator = $this->container->get('translator');
        return $translator->trans($type);
    }

    public function searchJournal()
    {
        $em = $this->container->get('doctrine.orm.default_entity_manager');
        $search = $this->container->get('fos_elastica.index.search.journal');
        $bool = new Bool();
        $match = new Query\Match();
        $match->setField('status', '3');
        $bool->addMust($match);
        if ($this->filter) {
            foreach ($this->filter as $key => $filter) {
                $filterObj = new \Elastica\Query\Match();
                $this->applyFilter($filterObj, $key, $filter);
                $bool->addMust($filterObj);
            }
        }

        $query = new Query();
        $query->setQuery($bool);
        $query->setFrom($this->getPage() * $this->getLimit());
        $query->setSize($this->getLimit());

        $aggregation = new Terms('institution');
        $aggregation->setField('institution.institution_type.id');
        $aggregation->setOrder('_count', 'desc');
        $qb = $em->createQueryBuilder();
        $qb->select('count(r.id)')
            ->from('OjsJournalBundle:InstitutionTypes', 'r');
        $aggregation->setSize($qb->getQuery()->getSingleScalarResult());
        $query->addAggregation($aggregation);

        $aggregation = new Terms('subject');
        $aggregation->setField('subjects.id');
        $aggregation->setOrder('_count', 'desc');
        $qb = $em->createQueryBuilder();
        $qb->select('count(r.id)')
            ->from('OjsJournalBundle:Subject', 'r');

        $aggregation->setSize($qb->getQuery()->getSingleScalarResult());

        $query->addAggregation($aggregation);

        $search = $search->search($query);

        $result = $search->getResults();
        $connection = $em->getConnection();
        $manager = new Registry($this->container, ['default' => $connection], ['default' => 'doctrine.orm.entity_manager'], 'default', 'default');
        $transformer = new ElasticaToModelTransformer($manager, 'OjsJournalBundle:Journal');
        $transformer->setPropertyAccessor($this->container->get('property_accessor'));
        $this->result = $transformer->transform($result);

        $this->setCount($search->getTotalHits());
        $this->addAggregation('institution', $this->transform($search->getAggregation('institution')['buckets'], 'OjsJournalBundle:InstitutionTypes'));
        $this->addAggregation('subject', $this->transform($search->getAggregation('subject')['buckets'], 'OjsJournalBundle:Subject'));
        return $this;
    }

    public function search()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        //$finder = $this->container->get('fos_elastica.finder.search.articles');
        $search = $this->container->get('fos_elastica.index.search.articles');

        $bool = new Bool();
        $multiMatch = new MultiMatch();
        $multiMatch->setFields(['subjects', 'title', 'keywords', 'subtitle', 'citations.raw', 'journal.title', 'journal.subtitle']);
        $multiMatch->setType('phrase_prefix');
        $multiMatch->setQuery($this->getParam('term'));
        $bool->addMust($multiMatch);

        if ($this->filter) {
            foreach ($this->filter as $key => $filter) {
                $filterObj = new \Elastica\Query\Match();
                $this->applyFilter($filterObj, $key, $filter);
                $bool->addMust($filterObj);
            }
        }

        $query = new Query();
        $query->setQuery($bool);
        $query->setFrom($this->getPage() * $this->getLimit());
        $query->setSize($this->getLimit());

        $aggregation = new Terms('journals');
        $aggregation->setField('journal.id');
        $aggregation->setOrder('_count', 'desc');
        $qb = $em->createQueryBuilder();
        $qb->select('count(r.id)')
            ->from('OjsJournalBundle:Journal', 'r')
            ->where($qb->expr()->eq('r.status', 3));
        $aggregation->setSize($qb->getQuery()->getSingleScalarResult());
        $query->addAggregation($aggregation);

        $aggregation = new Terms('authors');
        $aggregation->setField('articleAuthors.author.id');
        $aggregation->setOrder('_count', 'desc');
        $qb = $em->createQueryBuilder();
        $qb->select('count(r.id)')
            ->from('OjsJournalBundle:Author', 'r');
        $aggregation->setSize($qb->getQuery()->getSingleScalarResult());

        $query->addAggregation($aggregation);


        $search = $search->search($query);
        $result = $search->getResults();
        $connection = $em->getConnection();
        $manager = new Registry($this->container, ['default' => $connection], ['default' => 'doctrine.orm.entity_manager'], 'default', 'default');
        $transformer = new ElasticaToModelTransformer($manager, 'OjsJournalBundle:Article');
        $transformer->setPropertyAccessor($this->container->get('property_accessor'));
        $this->result = $transformer->transform($result);

        $this->setCount($search->getTotalHits());
        $this->addAggregation('journal', $this->transform($search->getAggregation('journals')['buckets'], 'OjsJournalBundle:Journal'));
        $this->addAggregation('author', $this->transform($search->getAggregation('authors')['buckets'], 'OjsJournalBundle:Author'));
        return $this;
    }

    /**
     * @param $bucket array
     * @param $class string
     * @return array
     */
    private function transform($bucket, $class)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $repo = $em->getRepository($class);
        if (!method_exists($repo, 'getByIds')) {
            throw new \BadMethodCallException("Undefined method.");
        }
        $ids = [];
        foreach ($bucket as $id) {
            $ids[] = $id['key'];
        }
        $data = $repo->getByIds($ids);
        $_data = [];
        foreach ($data as $value) {
            $_data[$value->getId()]['data'] = $value;
            foreach ($bucket as $val) {
                if ((int)$val['key'] == (int)$value->getId()) {
                    $_data[$value->getId()]['bucket'] = $val;
                }
            }
        }
        return $_data;
    }

    /**
     * @return float
     */
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
        return $this->page - 1;
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

    /**
     * @param $value
     * @param $key
     */
    public function addAggregation($key, $value)
    {
        $this->aggregations[$key] = $value;
    }

    /**
     * @return Array
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    public function addFilter($key, $value)
    {
        $this->filter[$key] = $value;
        return $this;
    }

    public function addFilters(array $filters)
    {
        $this->filter = array_merge($this->filter, $filters);
        return $this;
    }

    public function applyFilter(Query\Match &$query, $key, $value)
    {
        switch ($key) {
            case 'journal':
                $query->setField('journal.id', $value);
                break;
            case 'author':
                $query->setField('articleAuthors.author.id', $value);
                break;
            case 'institution':
                $query->setField('institution.institution_type.id', $value);
                break;
            case'subject':
                $query->setField('subjects.id', $value);
                break;
            default:
                throw new \ErrorException("Filter not exist. allowed filters: journal, author, institution, subject");
        }
    }

}