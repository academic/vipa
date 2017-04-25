<?php

namespace Vipa\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait JournalTrait
{
    /**
     * @var Journal
     * @ORM\ManyToOne(targetEntity="Vipa\JournalBundle\Entity\Journal")
     */
    protected $journal;

    /**
     * @return $this
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * @param Journal $journal
     * @return $this
     */
    public function setJournal(Journal $journal)
    {
        $this->journal = $journal;

        return $this;
    }
}
