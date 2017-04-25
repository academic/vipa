<?php

namespace Vipa\SiteBundle\Event;

use Vipa\JournalBundle\Entity\ArticleFile;
use Symfony\Component\EventDispatcher\Event;

class DownloadArticleFileEvent extends Event
{
    /**
     * @var ArticleFile
     */
    protected $articleFile;

    /**
     * DownloadArticleFileEvent constructor.
     * @param ArticleFile $articleFile
     */
    public function __construct($articleFile)
    {
        $this->articleFile = $articleFile;
    }

    /**
     * @return ArticleFile
     */
    public function getArticleFile()
    {
        return $this->articleFile;
    }
}