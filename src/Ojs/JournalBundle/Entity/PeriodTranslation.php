<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ojs\CoreBundle\Entity\DisplayTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslation;

/**
 * JournalPeriod
 */
class PeriodTranslation extends AbstractTranslation
{
    use DisplayTrait;

    /**
     * @Prezent\Translatable(targetEntity="Ojs\JournalBundle\Entity\Period")
     */
    protected $translatable;

    /**
     * @var integer
     */
    private $period;

    /**
     * @return int
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @param int $period
     */
    public function setPeriod($period)
    {
        $this->period = $period;
    }
}
