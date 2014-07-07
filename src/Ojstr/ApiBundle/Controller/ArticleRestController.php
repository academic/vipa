<?php

namespace Ojstr\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use Ojstr\UserBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

class ArticleRestController extends FOSRestController {

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Articles"
     * )
     * @Get("/articles/bulk/{page}/{limit}")
     */
    public function getArticlesAction($page = 0, $limit = 10) { 
        $articles = $this->getDoctrine()->getManager()
                ->createQuery('SELECT a FROM OjstrJournalBundle:Article a')
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
     * 
     */
    public function getArticleAction($id) {
        $article = $this->getDoctrine()->getRepository('OjstrJournalBundle:Article')->find($id);
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
    public function postArticleBulkcitationAction($id, Request $request) {
        $citesStr = $request->get('cites');
        if (empty($citesStr)) {
            throw new HttpException(400, 'Missing parameter : cites ');
        }
        $cites = json_decode($citesStr, TRUE);
        if (empty($cites) || !is_array($cites)) {
            throw new HttpException(400, 'Missing parameter : cites ');
        }
        foreach ($cites as $cite) {
            $citation = new \Ojstr\JournalBundle\Entity\Citation();
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
    public function postArticleCitationAction($id, Request $request) {
        $citation = new \Ojstr\JournalBundle\Entity\Citation();
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
     *  description="Get citation data of an article" ,
     *  method="POST"
     * )
     */
    public function getArticleCitationsAction($id) {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('OjstrJournalBundle:Article')->find($id);
        return $article->getCitations();
    }

    /**
     * 
     * @param integer $id
     * @param \Ojstr\JournalBundle\Entity\Citation $citation
     * @param Request|array $request
     * @return \Ojstr\JournalBundle\Entity\Article
     */
    private function addCitation2Article($id, \Ojstr\JournalBundle\Entity\Citation $citation, $request) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($citation);
        $em->flush();
        $citationSettingKeys = $this->container->getParameter('citation_setting_keys');
        // check and insert citation 
        /* @var $article \Ojstr\JournalBundle\Entity\Article  */
        $article = $em->getRepository('OjstrJournalBundle:Article')->find($id);
        $article->addCitation($citation);
        $em->persist($citation);
        $em->flush();
        foreach ($citationSettingKeys as $key => $desc) {
            $param = is_array($request) ? (isset($request[$key]) ? $request[$key] : NULL) : $request->get('setting_' . $key);
            if (!empty($param)) {
                $citationSetting = new \Ojstr\JournalBundle\Entity\CitationSetting();
                $citationSetting->setCitation($citation);
                $citationSetting->setSetting($key);
                $citationSetting->setValue($param);
                $em->persist($citationSetting);
                $em->flush();
            }
        }
        return $article;
    }

}
