<?php

namespace Ojs\AnalyticsBundle\Entity;

use Ojs\AnalyticsBundle\Traits\DownloadableTrait;
use Ojs\JournalBundle\Entity\Article;

/**
 * ArticleFileStatistic
 */
class ArticleFileStatistic extends Statistic
{
    use DownloadableTrait;

    /**
     * @var Article
     */
    private $articleFile;

    /**
     * @return Article
     */
    public function getArticleFile()
    {
        return $this->articleFile;
    }

    /**
     * @param Article $article
     */
    public function setArticleFile($article)
    {
        $this->articleFile = $article;
    }
}

