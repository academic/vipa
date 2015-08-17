<?php

namespace Ojs\AnalyticsBundle\Entity;

use Ojs\AnalyticsBundle\Traits\ViewableTrait;
use Ojs\JournalBundle\Entity\Article;

/**
 * ArticleStatistic
 */
class ArticleStatistic extends Statistic
{
    use ViewableTrait;

    /**
     * @var Article
     */
    private $article;

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param Article $article
     */
    public function setArticle($article)
    {
        $this->article = $article;
    }
}

