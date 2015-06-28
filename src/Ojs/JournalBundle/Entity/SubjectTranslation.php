<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

class SubjectTranslation extends AbstractPersonalTranslation
{
    private $subject;

    private $description;

    /**
     * Convinient constructor
     *
     * @param string $locale
     * @param string $field
     * @param string $value
     */
    public function __construct($locale, $field, $value)
    {
        $this->setLocale($locale);
        $this->setField($field);
        $this->setContent($value);
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public  function getSubject()
    {
        return $this->subject;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public  function getDescription()
    {
        return $this->description;
    }

    protected $object;
}