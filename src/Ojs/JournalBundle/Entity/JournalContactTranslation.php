<?php

namespace Ojs\JournalBundle\Entity;

/**
 * JournalContactTranslation
 */
class JournalContactTranslation
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $content;

    /**
     * @var \Ojs\JournalBundle\Entity\JournalContact
     */
    private $object;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set locale
     *
     * @param string $locale
     *
     * @return JournalContactTranslation
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set field
     *
     * @param string $field
     *
     * @return JournalContactTranslation
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return JournalContactTranslation
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set object
     *
     * @param \Ojs\JournalBundle\Entity\JournalContact $object
     *
     * @return JournalContactTranslation
     */
    public function setObject(\Ojs\JournalBundle\Entity\JournalContact $object = null)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * Get object
     *
     * @return \Ojs\JournalBundle\Entity\JournalContact
     */
    public function getObject()
    {
        return $this->object;
    }
}

