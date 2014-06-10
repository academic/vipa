<?php

namespace Ojstr\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use Ojstr\UserBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use Ojstr\UserBundle\Form\UserRestType;

class ArticleRestController extends FOSRestController {

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Article Action"
     * )
     */
    public function getArticleAction($id) {
        $article = $this->getDoctrine()->getRepository('OjstrJournalBundle:Article')->find($id);
        if (!is_object($article)) {
            $this->notFound();
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
     *         200="Returned when successful"
     *  }
     * )
     */
    public function postArticleBulkcitationAction($id, Request $request) {
        $citesStr = $request->get('cites');
        if (empty($citesStr)) {
            throw new HttpException(400, 'Missing parameter : cites ');
        }
        $cites = json_decode($citesStr);
        echo "<pre>";
        print_r($cites);
        exit();
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
        $em = $this->getDoctrine()->getManager();
        $citationSettingKeys = $this->container->getParameter('citation_setting_keys');
        // check and insert citation 
        /* @var $article \Ojstr\JournalBundle\Entity\Article  */
        $article = $em->getRepository('OjstrJournalBundle:Article')->find($id);
        $citation = new \Ojstr\JournalBundle\Entity\Citation();
        $citation->setRaw($request->get('raw'));
        $citation->setOrderNum($request->get('orderNum', 0));
        $citation->setType($request->get('type'));
        $citation->addArticle($article);
        $em->persist($article);
        $em->flush();
        foreach ($citationSettingKeys as $key => $v) {
            $param = $request->get('setting_' . $key);
            if (!empty($param)) {
                $citationSetting = new \Ojstr\JournalBundle\Entity\CitationSetting();
                $citationSetting->setCitation($citation);
                $citationSetting->setSetting($key);
                $citationSetting->setValue($param);
                $em->persist($citationSetting);
                $em->flush();
            }
        }
    }

    /**
     * 
     * @ApiDoc(
     *  resource=true,
     *  description="Get citation data of an article" ,
     *  method="POST"
     * )
     */
    public function getArticleCitationsAction($id, Request $request) {
        
    }

}
