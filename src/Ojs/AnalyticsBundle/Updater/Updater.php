<?php
/**
 * www
 */

namespace Ojs\AnalyticsBundle\Updater;


use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManager;

class Updater {
    /** @var DocumentManager  */
    protected $dm;
    /** @var EntityManager  */
    protected $em;
    public function __construct(EntityManager $em, DocumentManager $dm)
    {
        $this->dm=$dm;
        $this->em=$em;
    }

}