<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ojs\CoreBundle\Entity\DisplayTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslation;

class JournalTranslation extends AbstractTranslation
{
    use DisplayTrait;
    /**
     * @Prezent\Translatable(targetEntity="Ojs\JournalBundle\Entity\Journal")
     */
    protected $translatable;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $subtitle;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $titleAbbr;
    
    /** 
     * @var  string 
     */
    protected $footerText;

    /**
     * @var  string
     */
    protected $mailSignature;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * @param string $subtitle
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
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
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getTitleAbbr()
    {
        return $this->titleAbbr;
    }

    /**
     * @param string $titleAbbr
     */
    public function setTitleAbbr($titleAbbr)
    {
        $this->titleAbbr = $titleAbbr;
    }

    /**
     * @return string
     */
    public function getFooterText()
    {
        return $this->footerText;
    }

    /**
     * @param string $footerText
     */
    public function setFooterText($footerText)
    {
        $this->footerText = $footerText;
    }

    /**
     * @return string
     */
    public function getMailSignature()
    {
        return $this->mailSignature;
    }

    /**
     * @param string $mailSignature
     * @return JournalTranslation
     */
    public function setMailSignature($mailSignature)
    {
        $this->mailSignature = $mailSignature;
        return $this;
    }
}
