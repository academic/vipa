<?php

namespace Vipa\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait ArticleTrait
{
    /**
     * @var Article
     * @ORM\ManyToOne(targetEntity="Vipa\JournalBundle\Entity\Article")
     */
    protected $article;

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param Article $article
     * @return $this
     */
    public function setArticle(Article $article)
    {
        $this->article = $article;

        return $this;
    }
}
