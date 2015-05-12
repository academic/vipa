<?php
/**
 * Created by PhpStorm.
 * User: emreyilmaz
 * Date: 12.05.15
 * Time: 15:15
 */

namespace Ojs\AnalyticsBundle\Twig;


use Ojs\AnalyticsBundle\Document\ObjectView;
use Ojs\JournalBundle\Entity\Article;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AnalyticsExtension
 * @package Ojs\AnalyticsBundle\Twig
 */
class AnalyticsExtension extends \Twig_Extension
{

    /** @var  ContainerInterface */
    private $container;

    /** @var \Doctrine\ODM\MongoDB\DocumentManager */
    private $dm;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->dm = $this->container->get('doctrine.odm.mongodb.document_manager');

    }

    public function getFilters()
    {
        return [
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('stat', [$this, 'stat'])
        ];
    }

    /**
     * @param $id
     * @param $entity
     * @param string $stat_type
     * @return mixed
     */
    public function stat($id, $entity, $stat_type = 'view')
    {
        $method = 'get' . ucfirst($stat_type) . 'Stat';
        return $this->{$method}($id, $entity);
    }

    /**
     * @param $id
     * @param $entity
     * @return string
     */
    private function getViewStat($id, $entity)
    {
        /** @var ObjectView $data */
        $data = $this->dm->getRepository('OjsAnalyticsBundle:ObjectView')
            ->findOneBy(['objectId' => $id, 'entity' => $entity]);
        if(!$data)
            return 0;
        return $data->getTotal();
    }

    /**
     * @param $id
     * @param $entity
     * @return string
     */
    private function getDownloadStat($id, $entity)
    {
        /** @var ObjectView $data */
        $data = $this->dm->getRepository('OjsAnalyticsBundle:ObjectDownload')
            ->findOneBy(['objectId' => $id, 'entity' => $entity]);
        if(!$data)
            return 0;
        return $data->getTotal();
    }

    public function fileStatsOfArticle(Article $article)
    {
        $files = $article->getArticlefiles();
        foreach ($files as $file) {
            //@todo github.com/okulbilisim/ojs/issues/572
        }

    }
    public function getName()
    {
        return "ojs_analytics_extension";
    }

} 