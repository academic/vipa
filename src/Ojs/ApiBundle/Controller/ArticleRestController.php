<?php

namespace Ojs\ApiBundle\Controller;

use Doctrine\Common\Collections\Collection;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\ArticleRepository;
use Ojs\JournalBundle\Entity\Citation;
use Ojs\JournalBundle\Entity\CitationSetting;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ArticleRestController extends FOSRestController
{

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Articles"
     * )
     * @Get("/articles/bulk/{page}/{limit}")
     *
     * @param  int   $page
     * @param  int   $limit
     * @return Article[]|array
     */
    public function getArticlesAction($page = 0, $limit = 10)
    {
        /** @var ArticleRepository $articleRepo */
        $articleRepo = $this->getDoctrine()->getManager()->getRepository('OjsJournalBundle:Article');


        $articles = $articleRepo->findAllByLimits($page, $limit);
        if (!is_array($articles)) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }

        return $articles;
    }

    /**
     *
     * @param $id
     * @return object
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Specific Article"
     * )
     * @Get("/article/{id}")
     */
    public function getArticleDetailAction($id)
    {
        $article = $this->getDoctrine()->getRepository('OjsJournalBundle:Article')->find($id);
        if (!is_object($article)) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }

        return $article;
    }

    /**
     * @param $id
     * @param Request $request
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Add bulk citation data to an article" ,
     *  method="POST",
     *  requirements={
     *      {
     *          "name"="cites",
     *          "dataType"="string",
     *          "requirement"="\d+",
     *          "description"="json encoded citations"
     *      }
     *  },
     *  statusCodes={
     *         204="Returned when successful"
     *  }
     * )
     */
    public function postArticleBulkCitationAction($id, Request $request)
    {
        $citesStr = $request->get('cites');
        if (empty($citesStr)) {
            throw new HttpException(400, 'Missing parameter : cites ');
        }
        $cites = json_decode($citesStr, true);
        if (empty($cites) || !is_array($cites)) {
            throw new HttpException(400, 'Missing parameter : cites ');
        }
        foreach ($cites as $cite) {
            $citation = new Citation();
            $citation->setRaw($cite['raw']);
            $citation->setOrderNum(isset($cite['orderNum']) ? $cite['orderNum'] : 0);
            $citation->setType($cite['type']);
            $settings = $cite['settings'];
            $this->addCitation2Article($id, $citation, $settings);
        }
    }

    /**
     *
     * @param  integer  $id
     * @param  Citation $citation
     * @param  Request  $request
     * @return Article
     */
    private function addCitation2Article($id, Citation $citation, $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($citation);
        $em->flush();
        $citationSettingKeys = $this->container->getParameter('citation_setting_keys');
        // check and insert citation
        /* @var $article Article */
        $article = $em->getRepository('OjsJournalBundle:Article')->find($id);
        if (!$article) {
            return array();
        }
        $article->addCitation($citation);
        $em->persist($citation);
        $em->flush();
        foreach ($citationSettingKeys as $key => $desc) {
            $param = is_array($request) ? (isset($request[$key]) ? $request[$key] : null) : $request->get(
                'setting_'.$key
            );
            if (!empty($param)) {
                $citationSetting = new CitationSetting();
                $citationSetting->setCitation($citation);
                $citationSetting->setSetting($key);
                $citationSetting->setValue($param);
                $em->persist($citationSetting);
                $em->flush();
            }
        }

        return $article;
    }

    /**
     * @param $id
     * @param  Request $request
     * @return Article
     *
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Add a citation data to an article" ,
     *  method="POST",
     *  requirements={
     *      {
     *          "name"="raw",
     *          "dataType"="string",
     *          "requirement"="\d+",
     *          "description"="raw citation in any format"
     *      }
     *  }
     * )
     */
    public function postArticleCitationAction($id, Request $request)
    {
        $citation = new Citation();
        $citation->setRaw($request->get('raw'));
        $citation->setOrderNum($request->get('orderNum', 0));
        $citation->setType($request->get('type'));
        $article = $this->addCitation2Article($id, $citation, $request);

        return $article;
    }

    /**
     * @param $id
     * @return Collection|Citation[]|void
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get citation data of an article"
     * )
     * @Get("/article/{id}/citations")
     */
    public function getArticleCitationsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Article $article */
        $article = $em->getRepository('OjsJournalBundle:Article')->find($id);
        if (!$article) {
            return array();
        }

        return $article->getCitations();
    }

    /**
     * @param  Request $request
     * @param $article_id
     * @return object
     *
     * @ApiDoc(
     *  description="Change article 'orderNum'",
     *  method="PATCH",
     *  requirements={
     *      {
     *          "name"="orderNum",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="change Article issue order"
     *      }
     *  }
     * )
     * @RestView()
     */
    public function orderArticlesAction(Request $request, $article_id)
    {
        return $this->patch('orderNum', $article_id, $request);
    }

    /**
     * @param $field
     * @param $article_id
     * @param  Request $request
     * @return object
     */
    protected function patch($field, $article_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $this->getDoctrine()->getRepository('OjsJournalBundle:Article')->find($article_id);
        if (!is_object($article)) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }
        switch ($field) {
            case 'orderNum':
                $article->setOrderNum($request->get('orderNum'));
                break;
            case
            'status':
                $article->setStatus($request->get('status'));
                break;
            default:
                break;
        }
        $em->flush();

        return $article;
    }

    /**
     * @param $articleId
     * @return Article
     *
     *
     * @ApiDoc(
     *  description="Increment Article 'orderNum'",
     *  method="PATCH"
     * )
     * @RestView()
     * @Patch("/articles/{articleId}/order/up")
     */
    public function incrementOrderNumArticlesAction($articleId)
    {
        return $this->upDownOrder($articleId, true);
    }

    protected function upDownOrder($articleId, $up = true)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Article $article */
        $article = $em->getRepository('OjsJournalBundle:Article')->find($articleId);
        if (!is_object($article)) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }
        $o = $article->getOrderNum();
        $article->setOrderNum($up ? ($o + 1) : ($o - 1));
        $em->flush();

        return $article;
    }

    /**
     * @param $articleId
     * @return Article
     *
     * @ApiDoc(
     *  description="Increment Article 'orderNum'",
     *  method="PATCH"
     * )
     * @RestView()
     * @Patch("/articles/{articleId}/order/down")
     */
    public function decrementOrderNumArticlesAction($articleId)
    {
        return $this->upDownOrder($articleId, false);
    }

    /**
     *
     * @param  Request $request
     * @param $article_id
     * @return object
     *
     * @ApiDoc(
     *  description="Change article 'status'",
     *  method="PATCH",
     *  requirements={
     *      {
     *          "name"="status",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Change Article status"
     *      }
     *  }
     * )
     * @RestView()
     */
    public function statusArticlesAction(Request $request, $article_id)
    {
        return $this->patch('status', $article_id, $request);
    }
}
