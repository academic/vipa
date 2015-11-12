<?php

namespace Ojs\JournalBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\AbstractType;

class SubmissionFormEvent extends Event
{
    /** @var AbstractType */
    private $type;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}