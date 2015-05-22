<?php
/**
 * Created by PhpStorm.
 * User: emreyilmaz
 * Date: 17.03.15
 * Time: 21:54
 */
namespace Ojs\WorkflowBundle\Hydrate;

use Ojs\WorkflowBundle\Document\ArticleReviewStep;
use OkulBilisim\CitationParser\Citation;
use OkulBilisim\CitationParser\Parser as CitationParser;

/**
 * Class Article
 * @package Ojs\WorkflowBundle\Hydrate
 */
class Article
{
    /**
     * @param array             $data
     * @param ArticleReviewStep $step
     */
    public function mapChanges(array $data, ArticleReviewStep &$step)
    {
        $articleRevised = $step->getArticleRevised();
        if (array_key_exists('article', $data['changes'])) {
            foreach ($data['changes']['article'] as $key => $article) {
                $articleRevised['articleData'][$key] = array_merge($articleRevised['articleData'][$key], $article);
            }
        }
        if (array_key_exists('author', $data['changes'])) {
            foreach ($data['changes']['author'] as $key => $author) {
                if (empty($author['firstName']) || empty($author['lastName'])) {
                    continue;
                }
                $articleRevised['authors'][$key - 1] = array_merge($articleRevised['authors'][$key - 1], $author);
            }
        }
        $citationParser = new CitationParser();
        if (array_key_exists('citation', $data['changes'])) {
            foreach ($data['changes']['citation'] as $key => $citation) {
                $parsedCitations = $citationParser->parse($citation)[0];
                $citation = $this->grepCitation($parsedCitations, $citation);
                if (empty($citation['title'])) {
                    continue;
                }
                $articleRevised['citation'][$key - 1] = array_merge($articleRevised['citation'][$key - 1], $citation);
            }
        }
        if (array_key_exists('files', $data['changes'])) {
            foreach ($data['changes']['files'] as $key => $file) {
                if ($data['files'][$key]) {
                    $file = array_merge($data['files'][$key], $file);
                }
                $articleRevised['files'][$key - 1] = array_merge($articleRevised['files'][$key - 1], $file);
            }
        }
        if (array_key_exists('author', $data)) {
            $data['author'] = $this->grepAuthors($data['author']);
            $articleRevised['authors'] = array_merge($articleRevised['authors'], $data['author']);
        }
        if (array_key_exists('citation', $data)) {
            foreach ($data['citation'] as $key => $citation) {
                $parsed = $citationParser->parse($citation);
                if ($parsed) {
                    $parsedCitations = $parsed[0];
                    $citation = $this->grepCitation($parsedCitations, $citation);
                    $articleRevised['citation'] = array_merge($articleRevised['citation'], [$citation]);
                }
            }
        }
        $step->setArticleRevised($articleRevised);
    }

    /**
     * @param  Citation $parsedCitations
     * @param $citation
     * @return array
     */
    private function grepCitation(Citation $parsedCitations, $citation)
    {
        $citation = [
            'type' => $parsedCitations->getType(),
            'author' => $parsedCitations->getAuthor(),
            'title' => $parsedCitations->getTitle(),
            'journal' => $parsedCitations->getJournal(),
            'volume' => $parsedCitations->getVolume(),
            'pages' => $parsedCitations->getPages(),
            'year' => $parsedCitations->getYear(),
            'number' => $parsedCitations->getNumber(),
            'raw' => $citation,
        ];

        return $citation;
    }

    /**
     * @param  array $authors
     * @return array
     */
    private function grepAuthors(array $authors)
    {
        $author = [];
        foreach ($authors['orcid'] as $key => $value) {
            $_author = [];
            isset($authors['orcid']) && isset($authors['orcid'][$key]) && $_author['orcid'] = $authors['orcid'][$key];
            isset($authors['order']) && isset($authors['order'][$key]) && $_author['order'] = $authors['order'][$key];
            isset($authors['title']) && isset($authors['title'][$key]) && $_author['title'] = $authors['title'][$key];
            isset($authors['initials']) && isset($authors['initials'][$key]) && $_author['initials'] = $authors['initials'][$key];
            isset($authors['firstName']) && isset($authors['firstName'][$key]) && $_author['firstName'] = $authors['firstName'][$key];
            isset($authors['middleName']) && isset($authors['middleName'][$key]) && $_author['middleName'] = $authors['middleName'][$key];
            isset($authors['lastName']) && isset($authors['lastName'][$key]) && $_author['lastName'] = $authors['lastName'][$key];
            isset($authors['email']) && isset($authors['email'][$key]) && $_author['email'] = $authors['email'][$key];
            if (!isset($_author['firstName']) || !isset($_author['lastName']) || empty($_author['firstName']) || empty($_author['lastName'])) {
                continue;
            }

            $author[$key] = $_author;
        }

        return $author;
    }
}
