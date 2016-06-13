<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ojs\CoreBundle\Entity\DisplayTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslation;

class SubmissionSettingTranslation extends AbstractTranslation
{
    use DisplayTrait;

    protected $id;

    /**
     * @Prezent\Translatable(targetEntity="Ojs\JournalBundle\Entity\SubmissionSetting")
     */
    protected $translatable;

    /**
     * @var string
     */
    private $submissionConfirmText;

    /**
     * @var string
     */
    private $submissionAbstractTemplate;

    /**
     * @var string
     */
    private $submissionCloseText;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSubmissionConfirmText()
    {
        return $this->submissionConfirmText;
    }

    /**
     * @param string $submissionConfirmText
     *
     * @return $this
     */
    public function setSubmissionConfirmText($submissionConfirmText)
    {
        $this->submissionConfirmText = $submissionConfirmText;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubmissionAbstractTemplate()
    {
        return $this->submissionAbstractTemplate;
    }

    /**
     * @param string $submissionAbstractTemplate
     *
     * @return $this
     */
    public function setSubmissionAbstractTemplate($submissionAbstractTemplate)
    {
        $this->submissionAbstractTemplate = $submissionAbstractTemplate;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubmissionCloseText()
    {
        return $this->submissionCloseText;
    }

    /**
     * @param string $submissionCloseText
     *
     * @return $this
     */
    public function setSubmissionCloseText($submissionCloseText)
    {
        $this->submissionCloseText = $submissionCloseText;

        return $this;
    }
}
