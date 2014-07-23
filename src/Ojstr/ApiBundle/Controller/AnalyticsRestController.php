<?php

namespace Ojstr\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

class AnalyticsRestController extends FOSRestController {

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get article total Views"
     * )
     * @Get("/articles/{id}/analytics/view/total")
     */
    public function getArticlesViewAction($id) {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $data = $dm->getRepository('OjstrAnalyticsBundle:ArticleView')->findByArticleId($id);
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
    public function getArticlesDownloadAction($id) {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $data = $dm->getRepository('OjstrAnalyticsBundle:ArticleDownload')->findByArticleId($id);
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
    public function getJournalsViewAction($id) {
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
    public function getJournalsDownloadAction($id) {
        return $this->journalArticleDownloadSummary($id);
    }

    /**
     * wrapper function to get all articles total *view* in a journal
     * @param integer $id
     * @return integer
     */
    protected function journalArticleDownloadSummary($id) {
        return $this->journalArticleSummaryStat($id, 'ArticleDownload');
    }

    /**
     * wrapper function to get all articles total *download* in a journal
     * @param integer $id
     * @return integer
     */
    protected function journalArticleViewSummary($id) {
        return $this->journalArticleSummaryStat($id, 'ArticleView');
    }

    protected function journalArticleSummaryStat($id, $documentName) {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $repo = $dm->getRepository('OjstrAnalyticsBundle:ArticleView');
        $result = $repo->createQueryBuilder('OjstrAnalyticsBundle:' . $documentName)
                ->field('journalId')->equals($id)
                ->getQuery()
                ->execute();
        return $result->count();
    }

}
