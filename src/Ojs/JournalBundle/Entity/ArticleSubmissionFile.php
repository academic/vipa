<?php

namespace Ojs\JournalBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Ojs\CoreBundle\Annotation\Display as Display;

/**
 * ArticleSubmissionFile
 * @GRID\Source(columns="id,label,locale,visible")
 */
class ArticleSubmissionFile extends SubmissionFile
{
    /** @var  Article */
    private $article;

    /**
     * Get article
     *
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set article
     *
     * @param  Article $article
     * @return SubmissionFile
     */
    public function setArticle(Article $article = null)
    {
        $this->article = $article;

        return $this;
    }
}
