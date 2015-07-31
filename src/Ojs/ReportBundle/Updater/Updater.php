<?php
/**
 * www
 */
namespace Ojs\ReportBundle\Updater;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManager;

/**
 * Class Updater
 * @package Ojs\ReportBundle\Updater
 */
class Updater
{
    /** @var DocumentManager */
    protected $dm;
    /** @var EntityManager */
    protected $em;

    /** @var \Twig_Extension */
    protected $post_extension;

    public function __construct(EntityManager $em, DocumentManager $dm, \Twig_Extension $postExtension)
    {
        $this->dm = $dm;
        $this->em = $em;
        $this->post_extension = $postExtension;
    }
}
