<?php
namespace Ojs\SearchBundle\Manager;

use Elastica\Filter;
use Elastica\Query;
use Elastica\Result;
use Elastica\ResultSet;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class $this
 * @package Ojs\SearchBundle\Manager
 */
class SearchManager
{
    /** @var  TranslatorInterface */
    private $translator;
    /** @var Router */
    private $router;

    public function __construct(
        TranslatorInterface $translator,
        Router $router = null
    )
    {
        $this->translator = $translator;
        $this->router = $router;
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
                $data['route'] = $this->router->generate('ojs_site_explore_index', $filterParam, true);
                break;
            case 'publisher':
                $data['name'] = $source['name'];
                $data['route'] = $this->router->generate('ojs_publisher_page', ['slug' => $source['slug']], true);
                break;
            case 'user':
                $data['name'] = $source['firstName'] . ' ' . $source['lastName'];
                $data['route'] = $this->router->generate('ojs_user_profile', ['slug' => $source['username']], true);
                break;
            case 'author':
                $data['name'] = $source['firstName'] . ' ' . $source['lastName'];
                $data['route'] = $this->generateAuthorUrl($object);
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
                        'publisher' => $article['journal']['publisher']['slug'],
                    ],
                    true
                );
        }
    }
    protected  $totalHit;
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
}
