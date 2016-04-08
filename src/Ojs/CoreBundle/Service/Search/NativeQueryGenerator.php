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
                    ['title', 3],
                    ['translations.title', 2],
                    ['description', 1],
                    ['translations.description', 1],
                ],
                'aggs' => [
                    'subjects.subject',
                    'publisher.name',
                    'periods.period',
                ]
            ],
            'articles' => [
                'fields' => [
                    ['title', 3],
                    ['translations.title', 2],
                    ['abstract', 1],
                    ['translations.abstract', 1],
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
                    'fullName',
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
                    'fullName',
                ],
                'aggs' => [
                    'title.title',
                    'subjects.subject',
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

    /**
     * @return array
     */
    private function getTagQueryParams()
    {
        return [
            'user' => [
                'tags',
            ],
            'publisher' => [
                'tags',
            ],
            'journal' => [
                'tags',
            ],
            'subject' => [
                'tags',
            ],
            'journal_page' => [
                'tags',
            ],
            'author' => [
                'tags',
            ],
            'articles' => [
                'keywords',
                'translations.keywords',
            ]
        ];
    }

    /**
     * @param $section
     * @return bool|array
     */
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
            $searchField = $field;
            $boost = 1;
            if(is_array($field)){
                $searchField = $field[0];
                $boost = $field[1];
            }
            $queryArray['query']['filtered']['query']['bool']['should'][] = [
                'query_string' => [
                    'query' => $section.'.'.$searchField.':"'.strtolower($journalQuery).'"',
                    'boost' => $boost,
                ]
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
                $transformArray = ['OR' => 'should', 'AND' => 'must', 'NOT' => 'must_not'];
                $termParse['condition'] = $transformArray[$condition];
            } else {
                $termParse['condition'] = 'should';
            }
            if (isset($termParse['condition'])) {
                $searchText = preg_replace('/'.$termParse['condition'].' /', '', $termText);
            } else {
                $searchText = $termText;
            }
            $termParse['searchText'] = explode('[', $searchText)[0];
            $termParse['searchField'] = explode('[', preg_replace('/]/', '', $searchText))[1];
            $termParse['section'] = explode('.', $termParse['searchField'])[0];
            $searchTermsParsed[] = $termParse;
        }
        return $searchTermsParsed;
    }

    /**
     * Advanced query generator
     *
     * @todo this function is not finished yet we must do more tests
     * @param $section
     * @return mixed
     */
    private function advancedQueryGenerator($section)
    {
        $sectionParams = $this->getSearchParamsBag()[$section];
        $from = ($this->getPage()-1)*$this->getSearchSize();
        $size = $this->getSearchSize();
        $queryArray['from'] = $from;
        $queryArray['size'] = $size;

        $advancedQuery = trim(preg_replace('/advanced:/', '', $this->query));

        $parsedAdvancedQueryArray = $this->parseSearchQuery($advancedQuery);

        foreach($parsedAdvancedQueryArray as $parsedAdvancedQuery){
            if($parsedAdvancedQuery['section'] !== $section){
                continue;
            }
            $queryArray['query']['filtered']['query']['bool'][$parsedAdvancedQuery['condition']][] = [
                'wildcard' => [ $parsedAdvancedQuery['searchField'] => '*'.strtolower($parsedAdvancedQuery['searchText']).'*' ]
            ];
            var_dump($queryArray);
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

    /**
     * @param $section
     * @return bool|null
     */
    private function tagQueryGenerator($section)
    {
        if(!in_array($section, array_keys($this->getTagQueryParams()))){
            return false;
        }
        $sectionParams = $this->getSearchParamsBag()[$section];
        $sectionTagParams = $this->getTagQueryParams()[$section];
        $from = ($this->getPage()-1)*$this->getSearchSize();
        $size = $this->getSearchSize();
        $queryArray['from'] = $from;
        $queryArray['size'] = $size;

        $tagQuery = trim(preg_replace('/tag:/', '', $this->query));
        foreach($sectionTagParams as $tagField){
            $queryArray['query']['filtered']['query']['bool']['should'][] = [
                'term' => [ $section.'.'.$tagField => strtolower($tagQuery) ]
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

    /**
     * @param $section
     * @return mixed
     */
    private function basicQueryGenerator($section)
    {
        $sectionParams = $this->getSearchParamsBag()[$section];
        $from = ($this->getPage()-1)*$this->getSearchSize();
        $size = $this->getSearchSize();
        $queryArray['from'] = $from;
        $queryArray['size'] = $size;
        foreach($sectionParams['fields'] as $field){
            $searchField = $field;
            $boost = 1;
            if(is_array($field)){
                $searchField = $field[0];
                $boost = $field[1];
            }
            if(empty($this->query)){
                $queryArray['query']['filtered']['query']['bool']['should'][] = [
                    'match_all' => []
                ];
            }else{
                $queryArray['query']['filtered']['query']['bool']['should'][] = [
                    'query_string' => [
                        'query' => $section.'.'.$searchField.':'.$this->query,
                        'boost' => $boost,
                    ]
                ];
            }
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
