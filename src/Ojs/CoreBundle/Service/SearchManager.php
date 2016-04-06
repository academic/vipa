<?php

namespace Ojs\CoreBundle\Service;

use Elastica\Result;
use Elastica\ResultSet;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class $this
 * @package Ojs\CoreBundle\Service
 */
class SearchManager
{
    protected $totalHit;

    /**
     * @var  TranslatorInterface
     */
    private $translator;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var int|null
     */
    private $journalId = null;

    /**
     * @var array
     */
    private $requestAggsBag = [];

    /**
     * SearchManager constructor.
     *
     * @param TranslatorInterface $translator
     * @param Router $router
     * @param RequestStack $requestStack
     */
    public function __construct(TranslatorInterface $translator, Router $router, RequestStack $requestStack)
    {
        $this->translator   = $translator;
        $this->router       = $router;
        $this->request      = $requestStack->getCurrentRequest();
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
            $objectDetail = $this->getObjectDetail($object);
            if (!isset($results[$objectType])) {
                if($objectDetail['route']){
                    $results[$objectType]['data'] = [];
                    $results[$objectType]['total_item'] = 1;
                    $results[$objectType]['type'] = $this->translator->trans($object->getType());
                }
            } else {
                if($objectDetail['route']) {
                    $results[$objectType]['total_item']++;
                }
            }
            if ($section == $objectType) {
                $result['detail'] = $objectDetail;
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
     * @return array
     */
    private function getSearchInJournalQueryParams()
    {
        return [
            ['user.journalUsers.journal.id' ,               'user._all'],
            ['articles.journal.id' ,                        'articles._all'],
            ['citation.articles.journal.id' ,               'citation._all'],
            ['author.articleAuthors.article.journal.id' ,   'author._all'],
        ];
    }

    /**
     * @param $journalId
     * @param $query
     * @return mixed
     */
    public function getSearchInJournalQuery($journalId, $query)
    {
        $queryArray['should'] = [];

        foreach($this->getSearchInJournalQueryParams() as $param){
            $journalField = $param[0];
            $searchField = $param[1];
            $queryArray['should'][] = [
                'bool' =>
                    [
                        'must' =>
                            [
                                [
                                    'match' => [ $journalField => $journalId ]
                                ],
                                [
                                    'match' => [ $searchField => [ 'query' => $query ]]
                                ],
                            ],
                    ],
            ];
        }
        return $queryArray;
    }

    /**
     * @return $this
     */
    public function setupJournalId()
    {
        if($this->request->query->has('journalId')){
            $this->journalId = (int)$this->request->query->get('journalId');
        }
        return $this;
    }

    /**
     * @return int|null
     */
    public function getJournalId()
    {
        return $this->journalId;
    }

    /**
     * @param $journalId
     * @return $this
     */
    public function setJournalId($journalId = null)
    {
        $this->journalId = $journalId;

        return $this;
    }

    public function getSearchParamsBag()
    {
        return [
            'user' => [
                'fields' => [
                    'username',
                    'firstName',
                    'lastName',
                    'email',
                    'tags',
                ],
                'aggs' => [
                    'title',
                    'subjects',
                    'journalUsers.journal.title'
                ]
            ],
            'articles' => [
                'fields' => [
                    'title',
                    'abstract',
                ],
                'aggs' => [
                    'journal.title',
                    'section.title',
                ]
            ],
            'publisher' => [
                'fields' => [
                    'name',
                ],
                'aggs' => [
                    'publisherType.name',
                ]
            ],
            'journal' => [
                'fields' => [
                    'title',
                    'description',
                ],
                'aggs' => [
                    'subjects.subject',
                    'publisher.name',
                    'periods.period',
                ]
            ],
            'author' => [
                'fields' => [
                    'firstName',
                    'lastName',
                    'middleName',
                ],
                'aggs' => [
                    'title',
                ]
            ],

        ];
    }

    public function setupRequestAggs()
    {
        if($this->request->query->has('aggs')){
            $this->requestAggsBag = $this->request->query->get('aggs');
        }
        $this->requestAggsBag = $this->request->query->set('aggs', []);
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
