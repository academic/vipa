<?php

namespace Ojs\AnalyticsBundle\Entity;

use Ojs\AnalyticsBundle\Traits\DownloadableTrait;
use Ojs\JournalBundle\Entity\ArticleFile;

/**
 * ArticleFileStatistic
 */
class ArticleFileStatistic extends Statistic
{
    use DownloadableTrait;

    /**
     * @var ArticleFile
     */
    private $articleFile;

    /**
     * @return ArticleFile
     */
    public function getArticleFile()
    {
        return $this->articleFile;
    }

    /**
     * @param ArticleFile $articleFile
     */
    public function setArticleFile($articleFile)
    {
        $this->articleFile = $articleFile;
    }
}

