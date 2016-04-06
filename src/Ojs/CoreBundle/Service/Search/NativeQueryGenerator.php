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

    private $nativeQuery = [];

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
                    'title',
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
            ['user.journalUsers.journal.id' ,               'user._all'],
            ['articles.journal.id' ,                        'articles._all'],
            ['citation.articles.journal.id' ,               'citation._all'],
            ['author.articleAuthors.article.journal.id' ,   'author._all'],
        ];
    }

    private function journalQueryGenerator($section)
    {
        $journalId = null;
        $explodeQuery = explode(' ', $this->query);
        foreach($explodeQuery as $value){
            if(preg_match('/journal:/', $value)){
                $journalId = (int)explode('journal:', $value)[1];
            }
        }
        $journalQuery = trim(preg_replace('/journal:'.$journalId.'/', '', $this->query));

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
                                    'match' => [ $searchField => [ 'query' => $journalQuery ]]
                                ],
                            ],
                    ],
            ];
        }
        return json_encode($queryArray);
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
        $queryArray['query']['bool'] = [];
        $sectionParams = $this->getSearchParamsBag()[$section];
        foreach($sectionParams['fields'] as $field){
            $queryArray['query']['bool']['should'][] = [
                'wildcard' => [ $section.'.'.$field => '*'.strtolower($this->query).'*' ]
            ];
            if($this->setupAggs){
                foreach($sectionParams['aggs'] as $agg){
                    $queryArray['aggs'][$agg] = [
                        'terms' => [
                            'field' => $section.'.'.$agg
                        ]
                    ];
                }
            }
        }
        return $queryArray;
    }
}
