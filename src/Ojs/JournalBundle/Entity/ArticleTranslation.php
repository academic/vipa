<?php
namespace Ojs\JournalBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslation;
class ArticleTranslation extends AbstractTranslation
{
    /**
     * @Prezent\Translatable(targetEntity="Ojs\JournalBundle\Entity\Article")
     */
    protected $translatable;

    /**
     * Original article title
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $subjects;

    /**
     * @var string
     */
    protected $subtitle;

    /**
     * @var string
     */
    protected $keywords;

    /**
     * @var string
     */
    protected $abstract;

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
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * @param string $subjects
     */
    public function setSubjects($subjects)
    {
        $this->subjects = $subjects;
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
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @param string $keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * @return string
     */
    public function getAbstract()
    {
        return $this->abstract;
    }

    /**
     * @param string $abstract
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;
    }
}