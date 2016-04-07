<?php

namespace Ojs\CoreBundle\Service\Search;

/**
 * Class $this
 * @package Ojs\CoreBundle\Service
 */
class NativeQueryGenerator
{
    /**
     * @var int
     */
    private $searchSize = 20;

    /**
     * @var null
     */
    private $query = null;

    /**
     * @var array
     */
    private $requestAggsBag = [];

    /**
     * @var array
     */
    private $nativeQuery = [];

    /**
     * @var int
     */
    private $page = 1;

    /**
     * @var bool
     */
    private $setupAggs = true;

    public function generateNativeQuery($section, $setupAggs = true)
    {
        $this->setupAggs = $setupAggs;
        if(preg_match('/journal:/', $this->getQuery())){

            $this->nativeQuery = $this->journalQueryGenerator($section);
        }elseif(preg_match('/advanced:/', $this->getQuery())){

            $this->nativeQuery = $this->advancedQueryGenerator($section);
        }elseif(preg_match('/tag:/', $this->getQuery())){

            $this->nativeQuery = $this->tagQueryGenerator($section);
        }else{

            $this->nativeQuery = $this->basicQueryGenerator($section);
        }
        return $this->nativeQuery;
    }

    /**
     * @return array
     */
    public function getNativeQuery()
    {
        return $this->nativeQuery;
    }

    /**
     * @param array $nativeQuery
     * @return $this
     */
    public function setNativeQuery($nativeQuery)
    {
        $this->nativeQuery = $nativeQuery;

        return $this;
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

    /**
     * @return int
     */
    public function getPage()
    {
        return (int)$this->page;
    }

    /**
     * @param int $page
     * @return $this
     */
    public function setPage($page = 1)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return int
     */
    public function getSearchSize()
    {
        return $this->searchSize;
    }

    /**
     * @param int $searchSize
     * @return $this
     */
    public function setSearchSize($searchSize)
    {
        $this->searchSize = $searchSize;

        return $this;
    }

    /**
     * @return bool|int
     */
    private function getJournalIdFromQuery()
    {
        $explodeQuery = explode(' ', $this->query);
        foreach($explodeQuery as $value){
            if(preg_match('/journal:/', $value)){
                return (int)explode('journal:', $value)[1];
            }
        }
        return false;
    }

    public function getSearchParamsBag()
    {
        return [
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
            'author' => [
                'fields' => [
                    'firstName',
                    'lastName',
                    'middleName',
                ],
                'aggs' => [
                    'title.title',
                ]
            ],
            'user' => [
                'fields' => [
                    'username',
                    'firstName',
                    'lastName',
                    'email',
                    'tags',
                ],
                'aggs' => [
                    'title.title',
                    'subjects',
                    'journalUsers.journal.title'
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

        ];
    }

    /**
     * @return array
     */
    private function getSearchInJournalQueryParams()
    {
        return [
            'user'      => 'user.journalUsers.journal.id',
            'articles'  => 'articles.journal.id',
            'citation'  => 'citation.articles.journal.id',
            'author'    => 'author.articleAuthors.article.journal.id',
        ];
    }

    private function journalQueryGenerator($section)
    {
        $journalId = null;
        $sectionParams = $this->getSearchParamsBag()[$section];
        $from = ($this->getPage()-1)*$this->getSearchSize();
        $size = $this->getSearchSize();
        $queryArray['from'] = $from;
        $queryArray['size'] = $size;

        $journalId = $this->getJournalIdFromQuery();
        $journalQuery = trim(preg_replace('/journal:'.$journalId.'/', '', $this->query));
        if(isset($this->getSearchInJournalQueryParams()[$section])){
            $journalIdField = $this->getSearchInJournalQueryParams()[$section];
        }else{
            return false;
        }

        foreach($sectionParams['fields'] as $field){
            $queryArray['query']['filtered']['query']['bool']['should'][] = [
                'wildcard' => [ $section.'.'.$field => '*'.strtolower($journalQuery).'*' ]
            ];
        }
        //add journal id filter
        $queryArray['query']['filtered']['filter']['bool']['must'][] = [
            'term' => [ $journalIdField => $journalId ]
        ];
        if(!empty($this->requestAggsBag)){
            foreach($this->requestAggsBag as $requestAggKey => $requestAgg){
                if(!in_array($requestAggKey, $sectionParams['aggs'])){
                    continue;
                }
                foreach($requestAgg as $aggValue){
                    $queryArray['query']['filtered']['filter']['bool']['must'][] = [
                        'term' => [ $section.'.'.$requestAggKey => $aggValue ]
                    ];
                }
            }
        }
        if($this->setupAggs){
            foreach($sectionParams['aggs'] as $agg){
                $queryArray['aggs'][$agg] = [
                    'terms' => [
                        'field' => $section.'.'.$agg
                    ]
                ];
            }
        }

        return $queryArray;
    }

    private function advancedQueryGenerator($section)
    {
        return $this->query;
    }

    private function tagQueryGenerator($section)
    {
        return $this->query;
    }

    private function basicQueryGenerator($section)
    {
        $sectionParams = $this->getSearchParamsBag()[$section];
        $from = ($this->getPage()-1)*$this->getSearchSize();
        $size = $this->getSearchSize();
        $queryArray['from'] = $from;
        $queryArray['size'] = $size;
        foreach($sectionParams['fields'] as $field){
            $queryArray['query']['filtered']['query']['bool']['should'][] = [
                'wildcard' => [ $section.'.'.$field => '*'.strtolower($this->query).'*' ]
            ];
        }
        if(!empty($this->requestAggsBag)){
            foreach($this->requestAggsBag as $requestAggKey => $requestAgg){
                if(!in_array($requestAggKey, $sectionParams['aggs'])){
                    continue;
                }
                foreach($requestAgg as $aggValue){
                    $queryArray['query']['filtered']['filter']['bool']['must'][] = [
                        'term' => [ $section.'.'.$requestAggKey => $aggValue ]
                    ];
                }
            }
        }
        if($this->setupAggs){
            foreach($sectionParams['aggs'] as $agg){
                $queryArray['aggs'][$agg] = [
                    'terms' => [
                        'field' => $section.'.'.$agg
                    ]
                ];
            }
        }
        return $queryArray;
    }
}
