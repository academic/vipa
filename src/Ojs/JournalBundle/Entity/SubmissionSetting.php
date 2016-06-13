<?php

namespace Ojs\JournalBundle\Entity;

use Ojs\CoreBundle\Entity\GenericEntityTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslatable;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Class SubmissionSetting
 * @package Ojs\JournalBundle\Entity
 */
class SubmissionSetting extends AbstractTranslatable implements JournalItemInterface
{
    use GenericEntityTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @Prezent\Translations(targetEntity="Ojs\JournalBundle\Entity\SubmissionSettingTranslation")
     */
    protected $translations;

    /**
     * @var bool
     */
    private $submissionEnabled;

    /**
     * @var Journal
     */
    private $journal;

    /**
     * Translation helper method
     * @param null $locale
     * @return mixed|null|\Ojs\JournalBundle\Entity\SubmissionSettingTranslation
     */
    public function translate($locale = null)
    {
        if (null === $locale) {
            $locale = $this->currentLocale;
        }
        if (!$locale) {
            throw new \RuntimeException('No locale has been set and currentLocale is empty');
        }
        if ($this->currentTranslation && $this->currentTranslation->getLocale() === $locale) {
            return $this->currentTranslation;
        }
        $defaultTranslation = $this->translations->get($this->getDefaultLocale());
        if (!$translation = $this->translations->get($locale)) {
            $translation = new SubmissionSettingTranslation();
            if (!is_null($defaultTranslation)) {
                $translation->setSubmissionCloseText($defaultTranslation->getSubmissionCloseText());
                $translation->setSubmissionAbstractTemplate($defaultTranslation->getSubmissionAbstractTemplate());
                $translation->setSubmissionConfirmText($defaultTranslation->getSubmissionConfirmText());
            }
            $translation->setLocale($locale);
            $this->addTranslation($translation);
        }
        $this->currentTranslation = $translation;

        return $translation;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function getSubmissionEnabled()
    {
        return $this->submissionEnabled;
    }

    /**
     * @param bool $submissionEnabled
     *
     * @return $this
     */
    public function setSubmissionEnabled($submissionEnabled)
    {
        $this->submissionEnabled = $submissionEnabled;

        return $this;
    }

    /**
     * @return Journal
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * @param Journal $journal
     *
     * @return $this
     */
    public function setJournal(Journal $journal)
    {
        $this->journal = $journal;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubmissionConfirmText()
    {
        return $this->getLogicalFieldTranslation('submissionConfirmText', false);
    }

    /**
     * @param string $submissionConfirmText
     *
     * @return $this
     */
    public function setSubmissionConfirmText($submissionConfirmText)
    {
        $this->translate()->setSubmissionConfirmText($submissionConfirmText);

        return $this;
    }

    /**
     * @return string
     */
    public function getSubmissionAbstractTemplate()
    {
        return $this->getLogicalFieldTranslation('submissionAbstractTemplate', false);
    }

    /**
     * @param string $submissionAbstractTemplate
     *
     * @return $this
     */
    public function setSubmissionAbstractTemplate($submissionAbstractTemplate)
    {
        $this->translate()->setSubmissionAbstractTemplate($submissionAbstractTemplate);

        return $this;
    }

    /**
     * @return string
     */
    public function getSubmissionCloseText()
    {
        return $this->getLogicalFieldTranslation('submissionCloseText', false);
    }

    /**
     * @param string $submissionCloseText
     *
     * @return $this
     */
    public function setSubmissionCloseText($submissionCloseText)
    {
        $this->translate()->setSubmissionCloseText($submissionCloseText);

        return $this;
    }
}
