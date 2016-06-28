<?php

namespace Ojs\JournalBundle\Entity;


use Ojs\CoreBundle\Entity\DisplayTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslation;
use JMS\Serializer\Annotation as JMS;

/**
 * Subject
 * @JMS\ExclusionPolicy("all")
 */
class SubjectTranslation extends AbstractTranslation
{
    use DisplayTrait;
    /**
     * @Prezent\Translatable(targetEntity="Ojs\JournalBundle\Entity\Subject")
     */
    protected $translatable;

    /**
     * @var string
     * @JMS\Expose
     * @JMS\Groups({"export"})
     */
    private $subject;

    /**
     * @var string
     */
    private $description;

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
