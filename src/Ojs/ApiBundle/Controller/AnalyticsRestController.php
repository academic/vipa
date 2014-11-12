<?php

namespace Ojs\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use Ojs\AnalyticsBundle\Document\ArticleView;
use Ojs\AnalyticsBundle\Document\ArticleViews;
use Ojs\AnalyticsBundle\Document\ObjectViews;
use Symfony\Component\HttpFoundation\Request;

class AnalyticsRestController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Update article view count",
     *  requirements={
     *      {
     *          "name"="page_url",
     *          "dataType"="string",
     *          "description"="Requested page url"
     *      }
     *  }
     * )
     * @Put("/{entity}/{id}/analytics/view/add")
     */
    public function putObjectViewAction(Request $request, $id, $entity)
    {
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $stat = new ObjectViews();
        $stat->setPageUrl($request->get("page_url"));
        $stat->setIpAddress($request->getClientIp());
        $stat->setLogDate(new \DateTime("now"));
        $stat->setObjectId($id);
        $stat->setEntity($entity);
        $dm->persist($stat);
        $dm->flush();

        return $stat;
    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get article total Views"
     * )
     * @Get("/{entity}/{id}/analytics/view/total")
     */
    public function getObjectViewAction(Request $request, $id, $entity)
    {
        $pageUrl = $request->get('page_url');
        $dm = $this->get('doctrine_mongodb')->getManager();
        $data = $dm->getRepository('OjsAnalyticsBundle:ObjectView')
            ->findBy(['pageUrl'=>$pageUrl]);
        return $data;
    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get article total download count"
     * )
     * @Get("/articles/{id}/analytics/download/total")
     */
    public function getArticlesDownloadAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $data = $dm->getRepository('OjsAnalyticsBundle:ArticleDownload')->findByArticleId($id);

        return $data;
    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get journal's articles' total view count"
     * )
     * @Get("/journals/{id}/analytics/view/total")
     */
    public function getJournalsViewAction($id)
    {
        return $this->journalArticleViewSummary($id);
    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get journal's articles' total download count"
     * )
     * @Get("/journals/{id}/analytics/download/total")
     */
    public function getJournalsDownloadAction($id)
    {
        return $this->journalArticleDownloadSummary($id);
    }

    /**
     * wrapper function to get all articles total *view* in a journal
     * @param  integer $id
     * @return integer
     */
    protected function journalArticleDownloadSummary($id)
    {
        return $this->journalArticleStatsSummary($id, 'ArticleDownload');
    }

    /**
     * wrapper function to get all articles total *download* in a journal
     * @param  integer $id
     * @return integer
     */
    protected function journalArticleViewSummary($id)
    {
        return $this->journalArticleStatsSummary($id, 'ArticleView');
    }

    protected function journalArticleStatsSummary($id, $documentName)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $repo = $dm->getRepository('OjsAnalyticsBundle:ArticleView');
        $result = $repo->createQueryBuilder('OjsAnalyticsBundle:' . $documentName)
            ->field('journalId')->equals($id)
            ->getQuery()
            ->execute();

        return $result->count();
    }

}
