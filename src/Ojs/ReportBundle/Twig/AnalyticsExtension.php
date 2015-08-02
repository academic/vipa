<?php

namespace Ojs\ReportBundle\Twig;

use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Class AnalyticsExtension
 * @package Ojs\ReportBundle\Twig
 */
class AnalyticsExtension extends \Twig_Extension
{

    /** @var DocumentManager */
    private $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function getFilters()
    {
        return [
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('stat', [$this, 'stat']),
        ];
    }

    /**
     * @param $id
     * @param $entity
     * @param  string $stat_type
     * @return mixed
     */
    public function stat($id, $entity, $stat_type = 'view')
    {
        $method = 'get'.ucfirst($stat_type).'Stat';

        return $this->{$method}($id, $entity);
    }

    public function getName()
    {
        return "ojs_analytics_extension";
    }
}
