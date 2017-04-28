<?php

namespace Vipa\JournalBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This collection holds article submission start data
 */
class ArticleSubmissionStart
{
    /**
     * @var Collection|ArticleSubmissionFile[]
     */
    private $articleSubmissionFiles;

    /**
     * @var string
     */
    private $checks;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articleSubmissionFiles = new ArrayCollection();
    }

    /**
     * @return Collection|ArticleSubmissionFile[]
     */
    public function getArticleSubmissionFiles()
    {
        return $this->articleSubmissionFiles;
    }

    /**
     * Add submissionFiles
     *
     * @param  ArticleSubmissionFile $articleSubmissionFile
     * @return $this
     */
    public function addArticleSubmissionFile(ArticleSubmissionFile $articleSubmissionFile)
    {
        if(!$this->articleSubmissionFiles->contains($articleSubmissionFile)){
            $this->articleSubmissionFiles->add($articleSubmissionFile);
        }

        return $this;
    }

    /**
     * Remove articleSubmissionFiles
     *
     * @param ArticleSubmissionFile $articleSubmissionFile
     */
    public function removeArticleSubmissionFile(ArticleSubmissionFile $articleSubmissionFile)
    {
        if($this->articleSubmissionFiles->contains($articleSubmissionFile)){
            $this->articleSubmissionFiles->removeElement($articleSubmissionFile);
        }
    }

    /**
     * @return string
     */
    public function getChecks()
    {
        return $this->checks;
    }

    /**
     * @param string $checks
     */
    public function setChecks($checks)
    {
        $this->checks = $checks;
    }
}
