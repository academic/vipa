<?php

namespace Vipa\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vipa\CoreBundle\Entity\DisplayTrait;
use Prezent\Doctrine\Translatable\Annotation as Prezent;
use Prezent\Doctrine\Translatable\Entity\AbstractTranslation;
use JMS\Serializer\Annotation as JMS;

/**
 * JournalPeriod
 * @JMS\ExclusionPolicy("all")
 */
class PeriodTranslation extends AbstractTranslation
{
    use DisplayTrait;

    /**
     * @Prezent\Translatable(targetEntity="Vipa\JournalBundle\Entity\Period")
     */
    protected $translatable;

    /**
     * @var integer
     * @JMS\Expose
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
