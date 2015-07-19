<?php
namespace Ojs\SearchBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Elastica\Aggregation\Terms;
use Elastica\Filter;
use Elastica\Query;
use Elastica\Result;
use Elastica\ResultSet;
use FOS\ElasticaBundle\Doctrine\ORM\ElasticaToModelTransformer;
use FOS\ElasticaBundle\Elastica\Index;
use Pagerfanta\Adapter\ElasticaAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class $this
 * @package Ojs\SearchBundle\Manager
 */
class SearchManager
{
    /** @var Index */
    private $index;
    /** @var EntityManagerInterface */
    private $em;
    /** @var RegistryInterface */
    private $registry;
    /** @var PropertyAccessorInterface */
    private $propertyAccessor;
    /** @var  TranslatorInterface */
    private $translator;
    private $term;

    private $finder;
    private $search;

    private $page = 0, $limit = 12, $result = null, $count = 0;

    private $totalHit = 0;

    private $param = [];

    /** @var Array */
    private $aggregations;

    /** @var  Array */
    private $filter;

    /** @var  Pagerfanta */
    private $pager;
    /** @var Router */
    private $router;

    public function __construct(
        Index $index,
        EntityManagerInterface $em,
        RegistryInterface $registry,
        PropertyAccessorInterface $propertyAccessor,
        TranslatorInterface $translator,
        Router $router = null
    )
    {
        $this->index = $index;
        $this->em = $em;
        $this->registry = $registry;
        $this->propertyAccessor = $propertyAccessor;
        $this->translator = $translator;
        $this->finder = $this->search = new \stdClass();
        $this->aggregations = [];
        $this->filter = [];
        $this->router = $router;
    }

    public function tagSearch()
    {
        $query = new Query\Bool();
        $must = new Query\Match();
        $must->setField('tags', $this->getParam('term'));
        $query->addMust($must);
        $return_data = [];
        /**
         * @var ResultSet $results
         */
        $results = $this->index->search($query);
        foreach ($results as $result) {
            /** @var Result $result */
            if (!isset($return_data[$result->getType()])) {
                $return_data[$result->getType()] = ['type', 'data'];
            }
            $return_data[$result->getType()]['type'] = $this->getTypeText($result->getType());
            if (isset($return_data[$result->getType()]['data'])) {
                $return_data[$result->getType()]['data'][] = $this->getObjectDetail($result);
            } else {
                $return_data[$result->getType()]['data'] = [$this->getObjectDetail($result)];
            }
        }
        $this->setCount($results->getTotalHits());

        return $return_data;
    }

    /**
     * @param  string $key
     * @return array
     */
    public function getParam($key = null)
    {
        if ($key) {
            return $this->param[$key];
        }

        return $this->param;
    }

    /**
     * @param  array $param
     * @return $this
     */
    public function setParam($param)
    {
        $this->param = $param;

        return $this;
    }

    public function getTypeText($type)
    {
        return $this->translator->trans($type);
    }

    /**
     * @param  int $count
     * @return $this
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    public function searchJournal($status = 3, $published = true)
    {
        $bool = new Query\Bool();
        $match = new Query\Match();
        $match->setField('status', $status);
        $bool->addMust($match);
        $match = new Query\Match();
        $match->setField('published', $published);
        $bool->addMust($match);

        if (!empty($this->filter)) {
            foreach ($this->filter as $key => $filter) {
                $filterObj = new Query\Match();
                $this->applyFilter($filterObj, $key, $filter);
                $bool->addMust($filterObj);
            }
        }

        $query = new Query();
        $query->setQuery($bool);
        $query->setFrom(($this->getPage() - 1) * $this->getLimit());
        $query->setSize($this->getLimit());

        $aggregation = new Terms('institution');
        $aggregation->setField('institution.institution_type.id');
        $aggregation->setOrder('_count', 'desc');
        $qb = $this->em->createQueryBuilder();
        $qb->select('count(r.id)')
            ->from('OjsJournalBundle:InstitutionTypes', 'r');
        $aggregation->setSize($qb->getQuery()->getSingleScalarResult());
        $query->addAggregation($aggregation);

        $aggregation = new Terms('subject');
        $aggregation->setField('subjects.id');
        $aggregation->setOrder('_count', 'desc');
        $qb = $this->em->createQueryBuilder();
        $qb->select('count(r.id)')
            ->from('OjsJournalBundle:Subject', 'r');

        $aggregation->setSize($qb->getQuery()->getSingleScalarResult());

        $query->addAggregation($aggregation);

        $search = $this->index->search($query);

        $result = $search->getResults();
        $transformer = new ElasticaToModelTransformer($this->registry, 'OjsJournalBundle:Journal');
        $transformer->setPropertyAccessor($this->propertyAccessor);
        $this->result = $transformer->transform($result);

        $this->setCount($search->getTotalHits());
        $this->addAggregation(
            'institution',
            $this->transform($search->getAggregation('institution')['buckets'], 'OjsJournalBundle:InstitutionTypes')
        );
        $this->addAggregation(
            'subject',
            $this->transform($search->getAggregation('subject')['buckets'], 'OjsJournalBundle:Subject')
        );

        return $this;
    }

    /**
     * @param  Query\Match $query
     * @param $key
     * @param $value
     * @throws \ErrorException
     */
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

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param  int $page
     * @return $this
     */
    public function setPage($page)
    {
        $this->page = $page;
        $page < 1 && ($this->page = 1);

        return $this;
    }

    /**
     * @return integer
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param $value
     * @param string $key
     */
    public function addAggregation($key, $value)
    {
        $this->aggregations[$key] = $value;
    }

    /**
     * @param $bucket array
     * @param string $class string
     * @return array
     */
    private function transform($bucket, $class)
    {
        $repo = $this->em->getRepository($class);
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

    public function search()
    {
        //$finder = $this->container->get('fos_elastica.finder.search.articles');

        $bool = new Query\Bool();
        $multiMatch = new Query\MultiMatch();
        $multiMatch->setFields(
            ['subjects', 'title', 'keywords', 'subtitle', 'citations.raw', 'journal.title', 'journal.subtitle']
        );
        $multiMatch->setType('phrase_prefix');
        $multiMatch->setQuery($this->getParam('term'));
        $bool->addMust($multiMatch);

        if ($this->filter) {
            foreach ($this->filter as $key => $filter) {
                $filterObj = new Query\Match();
                $this->applyFilter($filterObj, $key, $filter);
                $bool->addMust($filterObj);
            }
        }
        $missing = new Filter\Missing("issue");
        $not = new Filter\BoolNot($missing);
        $notQ = new Query\Filtered();
        $notQ->setFilter($not);
        $bool->addMust($notQ);

        $query = new Query();
        $query->setQuery($bool);
        $query->setFrom($this->getPage() * $this->getLimit());
        $query->setSize($this->getLimit());

        $aggregation = new Terms('journals');
        $aggregation->setField('journal.id');
        $aggregation->setOrder('_count', 'desc');
        $qb = $this->em->createQueryBuilder();
        $qb->select('count(r.id)')
            ->from('OjsJournalBundle:Journal', 'r')
            ->where($qb->expr()->eq('r.status', 3));
        $aggregation->setSize($qb->getQuery()->getSingleScalarResult());
        $query->addAggregation($aggregation);

        $aggregation = new Terms('authors');
        $aggregation->setField('articleAuthors.author.id');
        $aggregation->setOrder('_count', 'desc');
        $qb = $this->em->createQueryBuilder();
        $qb->select('count(r.id)')
            ->from('OjsJournalBundle:Author', 'r');
        $aggregation->setSize($qb->getQuery()->getSingleScalarResult());

        $query->addAggregation($aggregation);

        $elasticaAdapter = new ElasticaAdapter($this->index, $query);

        $pagerFanta = new Pagerfanta($elasticaAdapter);
        $pagerFanta->setMaxPerPage($this->getLimit());

        $pagerFanta->setCurrentPage($this->getPage());
        /** @var ResultSet $search */
        $search = $pagerFanta->getCurrentPageResults();
        $result = $search->getResults();//$search->getResults();
        $this->pager = $pagerFanta;
        $transformer = new ElasticaToModelTransformer($this->registry, 'OjsJournalBundle:Article');
        $transformer->setPropertyAccessor($this->propertyAccessor);
        $this->result = $transformer->transform($result);

        $this->setCount($pagerFanta->getNbResults());
        $this->addAggregation(
            'journal',
            $this->transform($search->getAggregation('journals')['buckets'], 'OjsJournalBundle:Journal')
        );
        $this->addAggregation(
            'author',
            $this->transform($search->getAggregation('authors')['buckets'], 'OjsJournalBundle:Author')
        );

        return $this;
    }

    /**
     * @return float
     */
    public function getPageCount()
    {
        return ceil($this->getCount() / $this->getLimit());
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param  mixed $result
     * @return $this
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * @return integer
     */
    public function getTotalHit()
    {
        return $this->totalHit;
    }

    /**
     * @param integer $totalHit
     * @return $this
     */
    public function setTotalHit($totalHit)
    {
        $this->totalHit = $totalHit;
        return $this;
    }

    /**
     * @param  int $limit
     * @return $this
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
     * @param $term
     * @return $this
     */
    public function setTerm($term)
    {
        $this->term = $term;

        return $this;
    }

    /**
     * @param  string $key
     * @param  mixed $value
     * @return $this
     */
    public function addParam($key, $value)
    {
        $this->param[$key] = $value;

        return $this;
    }

    /**
     * @return Array
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function addFilter($key, $value)
    {
        $this->filter[$key] = $value;

        return $this;
    }

    /**
     * @param  array $filters
     * @return $this
     */
    public function addFilters(array $filters)
    {
        $this->filter = array_merge($this->filter, $filters);

        return $this;
    }

    /**
     * @return Pagerfanta
     */
    public function getPager()
    {
        return $this->pager;
    }

    /**
     * @param  Pagerfanta $pager
     * @return $this
     */
    public function setPager($pager)
    {
        $this->pager = $pager;

        return $this;
    }

    public function parseSearchQuery($searchTerm)
    {
        $searchTermsParsed = [];
        $searchTerms = array_slice(explode(')', $searchTerm), 0, -1);
        foreach ($searchTerms as $term) {
            $termParse = [];
            $termText = preg_replace('/\(/', '', trim($term));
            $condition = explode(' ', $termText)[0];
            if (in_array($condition, ['OR', 'NOT', 'AND'])) {
                $termParse['condition'] = $condition;
            } else {
                $termParse['condition'] = 'OR';
            }
            if (isset($termParse['condition'])) {
                $searchText = preg_replace('/' . $termParse['condition'] . ' /', '', $termText);
            } else {
                $searchText = $termText;
            }
            $termParse['searchText'] = explode('[', $searchText)[0];
            $termParse['searchField'] = explode('[', preg_replace('/]/', '', $searchText))[1];
            $searchTermsParsed[] = $termParse;
        }

        return $searchTermsParsed;
    }

    /**
     * @param ResultSet $resultSet
     * @param $section
     * @return array
     */
    public function buildResultsObject(ResultSet $resultSet, $section)
    {
        $results = [];
        /**
         * @var Result $object
         */
        foreach ($resultSet as $object) {
            $objectType = $object->getType();
            if (!isset($results[$objectType])) {
                $results[$objectType]['data'] = [];
                $results[$objectType]['total_item'] = 1;
                $results[$objectType]['type'] = $this->translator->trans($object->getType());
            } else {
                $results[$objectType]['total_item']++;
            }
            if ($section == $objectType) {
                $result['detail'] = $this->getObjectDetail($object);
                $result['source'] = $object->getSource();
                if ($result['detail']['route']) {
                    $results[$objectType]['data'][] = $result;
                }
            }

        }
        //set only acceptable count for selected section
        if (!empty($section) && isset($results[$section])) {
            $results[$section]['total_item'] = count($results[$section]['data']);
        }
        foreach ($results as $result) {
            $this->setTotalHit($this->getTotalHit() + $result['total_item']);
        }
        return $results;
    }

    /**
     * @param Result $object
     * @return mixed
     */
    private function getObjectDetail(Result $object)
    {
        $objectType = $object->getType();
        $source = $object->getSource();
        switch ($objectType) {
            case 'issue':
                $data['name'] = empty($source['title']) ? $this->generateIssueUrl($object) : $source['title'];
                $data['route'] = $this->generateIssueUrl($object);
                break;
            case 'journal':
                $data['name'] = $source['title'];
                $data['route'] = $this->generateJournalUrl($object);
                break;
            case 'articles':
                $data['name'] = $source['title'];
                $data['route'] = $this->generateArticleUrl($object);
                break;
            case 'subject':
                $data['name'] = $source['subject'];
                $filterParam['filter'] = ['subject' => $object->getId()];
                $data['route'] = $this->router->generate('ojs_journals_index', $filterParam, true);
                break;
            case 'institution':
                $data['name'] = $source['name'];
                $data['route'] = $this->router->generate('ojs_institution_page', ['slug' => $source['slug']], true);
                break;
            case 'user':
                $data['name'] = $source['firstName'] . ' ' . $source['lastName'];
                $data['route'] = $this->router->generate('ojs_user_profile', ['slug' => $source['username']], true);
                break;
            case 'author':
                $data['name'] = $source['firstName'] . ' ' . $source['lastName'];
                $data['route'] = $this->generateAuthorUrl($object);
                break;
            case 'file':
                $data['name'] = $source['name'];
                $data['route'] = $this->router->generate('ojs_file_download', ['id' => $object->getId()], true);
                break;
            case 'page':
                $data['name'] = $source['title'];
                $data['route'] = '#';
                break;
            case 'citation':
                $data['name'] = $source['raw'];
                $data['route'] = '#';
                break;
            default:
                $data['name'] = $objectType;
                $data['route'] = '#';
                break;
        }
        return $data;
    }

    /**
     * @param  Result $issueObject
     * @return string
     */
    private function generateIssueUrl(Result $issueObject)
    {
        $source = $issueObject->getSource();
        return $this->router
            ->generate(
                'ojs_issue_page',
                [
                    'id' => $issueObject->getId(),
                    'journal_slug' => $source['journal']['slug'],
                    'institution' => $source['journal']['institution']['slug'],
                ],
                true
            );
    }

    /**
     * @param  Result $journalObject
     * @return string
     */
    private function generateJournalUrl(Result $journalObject)
    {
        $source = $journalObject->getSource();
        return $this->router
            ->generate(
                'ojs_journal_index',
                [
                    'slug' => $source['slug'],
                    'institution' => $source['institution']['slug']
                ],
                true
            );
    }

    /**
     * @param  Result $articleObject
     * @return string
     */
    private function generateArticleUrl(Result $articleObject)
    {
        $source = $articleObject->getSource();
        if (!isset($source['issue']['id'])) {
            return false;
        }
        return $this->router
            ->generate(
                'ojs_article_page',
                [
                    'slug' => $source['journal']['slug'],
                    'article_id' => $articleObject->getId(),
                    'issue_id' => $source['issue']['id'],
                    'institution' => $source['journal']['institution']['slug'],
                ],
                true
            );
    }

    /**
     * @param  Result $authorObject
     * @return string
     */
    private function generateAuthorUrl(Result $authorObject)
    {
        $source = $authorObject->getSource();
        if (!empty($source['user'])) {
            return $this->router
                ->generate(
                    'ojs_user_profile',
                    [
                        'slug' => $source['user']['username']
                    ],
                    true
                );
        } else {
            if (!isset($source['articleAuthors'][0]['article'])) {
                return false;
            }
            $article = $source['articleAuthors'][0]['article'];
            if (!isset($article['issue']['id'])) {
                return false;
            }
            return $this->router
                ->generate(
                    'ojs_article_page',
                    [
                        'slug' => $article['journal']['slug'],
                        'article_id' => $article['id'],
                        'issue_id' => $article['issue']['id'],
                        'institution' => $article['journal']['institution']['slug'],
                    ],
                    true
                );
        }
    }
}
