<?php

namespace Ojs\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojs\UserBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use Noxlogic\RateLimitBundle\Annotation\RateLimit;

class ArticleRestController extends FOSRestController
{

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Articles"
     * )
     * @Get("/articles/bulk/{page}/{limit}")
     * @RateLimit(limit=1000, period=3600)
     */
    public function getArticlesAction($page = 0, $limit = 10)
    {
        $articles = $this->getDoctrine()->getManager()
                ->createQuery('SELECT a FROM OjsJournalBundle:Article a')
                ->setFirstResult($page)
                ->setMaxResults($limit)
                ->getResult();
        if (!is_array($articles)) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }

        return $articles;
    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Specific Article"
     * )
     * @Get("/article/{id}")
     */
    public function getArticleAction($id)
    {
        $article = $this->getDoctrine()->getRepository('OjsJournalBundle:Article')->find($id);
        if (!is_object($article)) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }

        return $article;
    }

    /**
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
    public function postArticleBulkcitationAction($id, Request $request)
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
            $citation = new \Ojs\JournalBundle\Entity\Citation();
            $citation->setRaw($cite['raw']);
            $citation->setOrderNum(isset($cite['orderNum']) ? $cite['orderNum'] : 0);
            $citation->setType($cite['type']);
            $settings = $cite['settings'];
            $this->addCitation2Article($id, $citation, $settings);
        }
    }

    /**
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
        $citation = new \Ojs\JournalBundle\Entity\Citation();
        $citation->setRaw($request->get('raw'));
        $citation->setOrderNum($request->get('orderNum', 0));
        $citation->setType($request->get('type'));
        $article = $this->addCitation2Article($id, $citation, $request);

        return $article;
    }

    /**
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
        $article = $em->getRepository('OjsJournalBundle:Article')->find($id);
        if(!$article)
            return null;
        return $article->getCitations();
    }

    /**
     *
     * @param  integer                              $id
     * @param  \Ojs\JournalBundle\Entity\Citation $citation
     * @param  Request|array                        $request
     * @return \Ojs\JournalBundle\Entity\Article
     */
    private function addCitation2Article($id, \Ojs\JournalBundle\Entity\Citation $citation, $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($citation);
        $em->flush();
        $citationSettingKeys = $this->container->getParameter('citation_setting_keys');
        // check and insert citation
        /* @var $article \Ojs\JournalBundle\Entity\Article  */
        $article = $em->getRepository('OjsJournalBundle:Article')->find($id);
        if(!$article){
            return null;
        }
        $article->addCitation($citation);
        $em->persist($citation);
        $em->flush();
        foreach ($citationSettingKeys as $key => $desc) {
            $param = is_array($request) ? (isset($request[$key]) ? $request[$key] : null) : $request->get('setting_' . $key);
            if (!empty($param)) {
                $citationSetting = new \Ojs\JournalBundle\Entity\CitationSetting();
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
     *
     * @ApiDoc(
     *  description="Increment Article 'orderNum'",
     *  method="PATCH"
     * )
     * @RestView()
     * @Patch("/articles/{articleId}/order/up")
     */
    public function incrementOrderumArticlesAction($articleId)
    {
        return $this->upDownOrder($articleId, true);
    }

    /**
     *
     * @ApiDoc(
     *  description="Increment Article 'orderNum'",
     *  method="PATCH"
     * )
     * @RestView()
     * @Patch("/articles/{articleId}/order/down")
     */
    public function decrementOrderumArticlesAction($articleId)
    {
        return $this->upDownOrder($articleId, false);
    }

    /**
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

    protected function patch($field, $article_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $this->getDoctrine()->getRepository('OjsJournalBundle:Article')->findOneById($article_id);
        if (!is_object($article)) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }
        /* @var  $user \Ojs\UserBundle\Entity\User */
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

    protected function upDownOrder($articleId, $up = true)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $this->getDoctrine()->getRepository('OjsJournalBundle:Article')->findOneById($articleId);
        if (!is_object($article)) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }
        /* @var  $user \Ojs\UserBundle\Entity\User */
        $o = $article->getOrderNum();
        $article->setOrderNum($up ? ($o + 1) : ($o - 1));
        $em->flush();

        return $article;
    }

}
