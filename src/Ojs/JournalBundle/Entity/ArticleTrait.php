<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait ArticleTrait
{
    /**
     * @var Article
     * @ORM\ManyToOne(targetEntity="Ojs\JournalBundle\Entity\Article")
     */
    protected $article;

    /**
     * @return $this
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
