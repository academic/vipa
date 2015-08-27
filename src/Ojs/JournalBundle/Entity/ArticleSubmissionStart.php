<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This collection holds article submission start data
 */
class ArticleSubmissionStart
{
    /**
     * @var Collection|ArticleFile[]
     */
    private $submissionFiles;

    /**
     * @var string
     */
    private $checks;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->submissionFiles = new ArrayCollection();
    }

    /**
     * @return Collection|ArticleFile[]
     */
    public function getSubmissionFiles()
    {
        return $this->submissionFiles;
    }

    /**
     * Add submissionFiles
     *
     * @param  SubmissionFile $articleFile
     * @return $this
     */
    public function addSubmissionFile(SubmissionFile $articleFile)
    {
        if(!$this->submissionFiles->contains($articleFile)){
            $this->submissionFiles->add($articleFile);
        }

        return $this;
    }

    /**
     * Remove submissionFiles
     *
     * @param SubmissionFile $articleFile
     */
    public function removeSubmissionFile(SubmissionFile $articleFile)
    {
        if($this->submissionFiles->contains($articleFile)){
            $this->submissionFiles->removeElement($articleFile);
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
