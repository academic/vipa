<?php

namespace Ojs\CoreBundle\Service\Search;

use Elastica\Result;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use FOS\ElasticaBundle\Elastica\Index;
use Pagerfanta\Adapter\FixedAdapter;
use Pagerfanta\Pagerfanta;

/**
 * Class $this
 * @package Ojs\CoreBundle\Service
 */
class SearchManager
{
    /**
     * @var int
     */
    protected $totalHit = 0;

    /**
     * @var int
     */
    protected $currectSectionHit = 0;

    /**
     * @var  TranslatorInterface
     */
    private $translator;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Index
     */
    private $searchIndex;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var ParameterBag
     */
    private $requestQuery;

    /**
     * @var string
     */
    private $section = null;

    /**
     * @var NativeQueryGenerator
     */
    private $nativeQueryGenerator;

    /**
     * @var array
     */
    private $resultSet;

    /**
     * @var array
     */
    private $aggs = [];

    /**
     * SearchManager constructor.
     *
     * @param TranslatorInterface $translator
     * @param Router $router
     * @param RequestStack $requestStack
     * @param NativeQueryGenerator $nativeQueryGenerator
     * @param Index $searchIndex
     */
    public function __construct(
        TranslatorInterface $translator,
        Router $router,
        RequestStack $requestStack,
        NativeQueryGenerator $nativeQueryGenerator,
        Index $searchIndex
    )
    {
        $this->translator           = $translator;
        $this->router               = $router;
        $this->request              = $requestStack->getCurrentRequest();
        $this->requestQuery         = $this->request->query;
        $this->nativeQueryGenerator = $nativeQueryGenerator;
        $this->searchIndex          = $searchIndex;
    }

    /**
     * @return NativeQueryGenerator
     */
    public function getNativeQueryGenerator()
    {
        return $this->nativeQueryGenerator;
    }

    /**
     * @param $searchTerm
     * @return array
     */
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
                $searchText = preg_replace('/'.$termParse['condition'].' /', '', $termText);
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
     * @param array $result
     * @return array
     */
    public function reOrderResultObjects(array $result)
    {
        return $this->sortArrayByArray($result, $this->resultsOrderArray());
    }

    /**
     * @return array
     */
    private function resultsOrderArray()
    {
        return ['journal', 'articles','author','user', 'issue', 'subject','publisher','page','citation'];
    }

    /**
     * @param array $array
     * @param array $orderArray
     * @return array
     */
    private function sortArrayByArray(array $array, array $orderArray)
    {
        $ordered = array();
        foreach($orderArray as $key) {
            if(array_key_exists($key,$array)) {
                $ordered[$key] = $array[$key];
                unset($array[$key]);
            }
        }
        return $ordered + $array;
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
                $data['route'] = $this->router->generate('ojs_site_explore_index', $filterParam, true);
                break;
            case 'publisher':
                $data['name'] = $source['name'];
                $data['route'] = $this->router->generate('ojs_publisher_page', ['slug' => $source['slug']], true);
                break;
            case 'user':
                $data['name'] = $source['firstName'].' '.$source['lastName'];
                $data['route'] = $this->router->generate('ojs_user_profile', ['slug' => $source['username']], true);
                break;
            case 'author':
                $data['name'] = $source['firstName'].' '.$source['lastName'];
                $data['route'] = $this->generateAuthorUrl($object);
                break;
            case 'page':
                $data['name'] = $source['title'];
                $data['route'] = '#';
                break;
            case 'citation':
                $data['name'] = $source['raw'];
                $data['route'] = $this->generateCitationUrl($object);
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
                    'publisher' => $source['journal']['publisher']['slug'],
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
                    'publisher' => $source['publisher']['slug']
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
                    'publisher' => $source['journal']['publisher']['slug'],
                ],
                true
            );
    }

    /**
     * @param  Result $citationObject
     * @return string
     */
    private function generateCitationUrl(Result $citationObject)
    {
        $source = $citationObject->getSource();
        //check article count
        if(count($source['articles'])< 1){
            return false;
        }
        //check article id is exists
        if(isset($source['articles'][0]['id'])){
            $article = $source['articles'][0];
        }else{
            return false;
        }
        //check article issue is exists
        if (isset($article['issue']['id'])) {
            $issue = $article['issue'];
        }else{
            return false;
        }
        //check article journal is exists
        if (isset($article['journal'])) {
            $journal = $article['journal'];
        }else{
            return false;
        }
        //check journal publisher is exists
        if (isset($journal['publisher'])) {
            $publisher = $journal['publisher'];
        }else{
            return false;
        }

        return $this->router
            ->generate(
                'ojs_article_page',
                [
                    'slug' => $journal['slug'],
                    'article_id' => $article['id'],
                    'issue_id' => $issue['id'],
                    'publisher' => $publisher['slug'],
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
        //check article count
        if(count($source['articleAuthors'])< 1){
            return false;
        }
        //check article id is exists
        if(isset($source['articleAuthors'][0]['article']['id'])){
            $article = $source['articleAuthors'][0]['article'];
        }else{
            return false;
        }
        //check article issue is exists
        if (isset($article['issue']['id'])) {
            $issue = $article['issue'];
        }else{
            return false;
        }
        //check article journal is exists
        if (isset($article['journal'])) {
            $journal = $article['journal'];
        }else{
            return false;
        }
        //check journal publisher is exists
        if (isset($journal['publisher'])) {
            $publisher = $journal['publisher'];
        }else{
            return false;
        }
        return $this->router
            ->generate(
                'ojs_article_page',
                [
                    'slug' => $journal['slug'],
                    'article_id' => $article['id'],
                    'issue_id' => $issue['id'],
                    'publisher' => $publisher['slug'],
                ],
                true
            );
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
     * @return mixed
     */
    public function getCurrectSectionHit()
    {
        return $this->currectSectionHit;
    }

    /**
     * @param $currectSectionHit
     * @return $this
     */
    public function setCurrectSectionHit($currectSectionHit)
    {
        $this->currectSectionHit = $currectSectionHit;

        return $this;
    }

    /**
     * @return $this
     */
    public function setupRequestAggs()
    {
        if($this->requestQuery->has('aggs')){
            $this->nativeQueryGenerator->setRequestAggsBag($this->requestQuery->get('aggs'));
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getRequestAggsBag()
    {
        return $this->nativeQueryGenerator->getRequestAggsBag();
    }

    /**
     * @param array $requestAggsBag
     * @return $this
     */
    public function setRequestAggsBag($requestAggsBag)
    {
        $this->nativeQueryGenerator->setRequestAggsBag($requestAggsBag);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param string $section
     * @return $this
     */
    public function setSection($section)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * @return $this
     */
    public function setupSection()
    {
        if(!$this->requestQuery->has('section')){
            return $this;
        }
        if(!in_array($this->requestQuery->get('section'), $this->getSectionList())){
            return $this;
        }
        $this->section = filter_var($this->requestQuery->get('section'), FILTER_SANITIZE_STRING);
        return $this;
    }

    public function getSectionList()
    {
        return array_keys($this->nativeQueryGenerator->getSearchParamsBag());
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->nativeQueryGenerator->getPage();
    }

    /**
     * @param int $page
     * @return $this
     */
    public function setPage($page)
    {
        $this->nativeQueryGenerator->setPage($page);

        return $this;
    }

    public function setupQuery()
    {
        if(!$this->requestQuery->has('q')){
            return $this;
        }
        $this->nativeQueryGenerator->setQuery(filter_var($this->requestQuery->get('q'), FILTER_SANITIZE_STRING));

        return $this;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->nativeQueryGenerator->getQuery();
    }

    /**
     * @param string $query
     * @return $this
     */
    public function setQuery($query)
    {
        $this->nativeQueryGenerator->setQuery($query);

        return $this;
    }

    /**
     * @return null
     */
    public function decideSection()
    {
        foreach($this->getSectionList() as $section){
            $nativeQuery = $this->nativeQueryGenerator->generateNativeQuery($section, false);
            if($nativeQuery === false){
                continue;
            }
            /** @var \Elastica\ResultSet $resultData */
            $resultData = $this->searchIndex->search($nativeQuery);
            if($resultData->count()> 0){
                return $section;
            }
        }
        return null;
    }

    public function setupQueryResultSet()
    {
        $results = [];
        foreach($this->getSectionList() as $section){
            $setupAggs = $section == $this->getSection()? true: false;
            $nativeQuery = $this->nativeQueryGenerator->generateNativeQuery($section, $setupAggs);
            if($nativeQuery === false){
                continue;
            }
            /** @var \Elastica\ResultSet $resultData */
            $resultData = $this->searchIndex->search($nativeQuery);
            if($resultData->getTotalHits() < 1){
                continue;
            }
            $this->setTotalHit($this->getTotalHit()+$resultData->getTotalHits());
            if($section !== $this->getSection()){
                $results[$section]['total_item'] = $resultData->getTotalHits();
                $results[$section]['type'] = $this->translator->trans($section);
                continue;
            }
            $this->setCurrectSectionHit($resultData->getTotalHits());

            /**
             * @var Result $object
             */
            foreach($resultData as $resultObject){
                $objectDetail = $this->getObjectDetail($resultObject);
                $results[$section]['total_item'] = $resultData->getTotalHits();
                $results[$section]['type'] = $this->translator->trans($section);
                $result['detail'] = $objectDetail;
                $result['source'] = $resultObject->getSource();
                $results[$section]['data'][] = $result;
            }
            $this->setAggs($resultData->getAggregations());
        }
        $this->setResultSet($results);
    }

    /**
     * @return array
     */
    public function getResultSet()
    {
        return $this->resultSet;
    }

    /**
     * @param array $resultSet
     * @return $this
     */
    public function setResultSet($resultSet)
    {
        $this->resultSet = $resultSet;

        return $this;
    }

    /**
     * @return array
     */
    public function getAggs()
    {
        return $this->aggs;
    }

    /**
     * @param array $aggs
     * @return $this
     */
    public function setAggs($aggs = [])
    {
        $this->aggs = $aggs;

        return $this;
    }

    public function getAggLink($aggKey, $bucketKey, $add = true)
    {
        $routeParams = $this->request->attributes->get('_route_params');
        $routeParams['page'] = 1;
        $requestQueryParams = $this->requestQuery->all();
        $requestAggsBag = $this->getRequestAggsBag();
        if($add){
            $requestAggsBag[$aggKey][] = $bucketKey;
        }else{
            $searchBucketKey = array_search($bucketKey, $requestAggsBag[$aggKey]);
            if($searchBucketKey !== false){
                unset($requestAggsBag[$aggKey][$searchBucketKey]);
            }
        }
        $setupAggs['aggs'] = $requestAggsBag;
        $allRouteParams = array_merge($routeParams, $requestQueryParams, $setupAggs);
        return $this->router->generate('ojs_search_index', $allRouteParams);
    }

    public function getPagerfanta()
    {
        $nbResults = $this->getCurrectSectionHit();
        $adapter = new FixedAdapter($nbResults, []);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setCurrentPage($this->getPage());
        $pagerfanta->setMaxPerPage($this->nativeQueryGenerator->getSearchSize());

        return $pagerfanta;
    }
}
