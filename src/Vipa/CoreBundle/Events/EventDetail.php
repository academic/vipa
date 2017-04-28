<?php

namespace Vipa\CoreBundle\Events;

class EventDetail
{
    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $group
     */
    private $group;

    /**
     * @var array $templateParams
     */
    private $templateParams;

    /**
     * EventDetail constructor.
     * @param string $name
     * @param string $group
     * @param array $templateParams
     */
    public function __construct($name, $group = 'journal', $templateParams = [])
    {
        $this->name = $name;
        $this->group = $group;
        $this->templateParams = $templateParams;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param string $group
     *
     * @return $this
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return array
     */
    public function getTemplateParams()
    {
        return $this->templateParams;
    }

    /**
     * @param array $templateParams
     *
     * @return $this
     */
    public function setTemplateParams($templateParams)
    {
        $this->templateParams = $templateParams;

        return $this;
    }
}
