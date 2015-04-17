<?php

namespace Ojs\ApiBundle\Controller;

use Doctrine\ORM\EntityManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use Elastica\Query;
use FOS\ElasticaBundle\Doctrine\ORM\ElasticaToModelTransformer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * PublicSearchRest may contain similar actions with SearchRest
 */
class PublicSearchRestController extends FOSRestController {

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="search Institutions",
     *  parameters={
     * {
     *          "name"="q",
     *          "dataType"="string",
     *          "required"="true",
     *          "description"="search term"
     *      },
     *      {
     *          "name"="verified",
     *          "dataType"="boolean",
     *          "required"="false",
     *          "description"="list only verified or not"
     *      },
     *      {
     *          "name"="page",
     *          "dataType"="integer",
     *          "required"="false",
     *          "description"="limit"
     *      }
     *  }
     * )
     * @Get("/public/search/institution")
     */
    public function getInstitutionsAction(Request $request)
    {
        $limit = $request->get('limit');
        $verified = $request->get('verified');
        $q = $request->get('q');
        $search = $this->container->get('fos_elastica.index.search.institution');

        $query = new Query\Bool();
        $q1 = new Query\Regexp('name', $q);
        $query->addMust($q1); 


        if ($verified) {  
            $must = new Query\Match();
            $must->setField('verified', $verified);
            $query->addMust($must);
        }

        $results = $search->search($query);
        $data = [];
        foreach ($results as $result) {
            $data[] = array_merge(array('id' => $result->getId()), $result->getData());
        }
        return $data;
    }


    /**
     * @param Request $request
     * @ApiDoc(
     *  resource=true,
     *  description="search Institutions",
     *  parameters={
     * {
     *          "name"="q",
     *          "dataType"="string",
     *          "required"="true",
     *          "description"="search term"
     *      },
     *      {
     *          "name"="page",
     *          "dataType"="integer",
     *          "required"="false",
     *          "description"="limit"
     *      }
     *  }
     * )
     * @Get("/public/search/tags")
     * @return array
     */
    public function getTagsAction(Request $request)
    {
        return [
            ['id'=>1,'name'=>'emre'],
            ['id'=>2,'name'=>'emrah'],
            ['id'=>3,'name'=>'emrullah'],
            ['id'=>4,'name'=>'emir'],
            ['id'=>5,'name'=>'emel'],
        ];
    }

    /**
     * @param Request $request
     * @ApiDoc(
     *  resource=true,
     *  description="search users",
     *  parameters={
     * {
     *          "name"="q",
     *          "dataType"="string",
     *          "required"="true",
     *          "description"="search term"
     *      },
     *      {
     *          "name"="page",
     *          "dataType"="integer",
     *          "required"="false",
     *          "description"="limit"
     *      }
     *  }
     * )
     * @Get("/public/search/user")
     * @return array
     */
    public function getUsersAction(Request $request){
        $limit = 12;
        $q = $request->get('q');
        $search = $this->container->get('fos_elastica.index.search.user');

        $prefix = new Query\Prefix();
        $prefix->setPrefix('username',strtolower($q));
        $qe = new Query();
        $qe->setQuery($prefix);


        $results = $search->search($prefix);
        $data = [];
        foreach ($results as $result) {
            $data[] = [
                'id'=>$result->getId(),
                'text'=>$result->getData()['username']." - ".$result->getData()['email'],
            ];
        }
        return $data;
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     * @ApiDoc(
     *  resource=true,
     *  description="get user by id"
     * )
     * @Get("/public/user/get/{id}")
     */
    public function getUserAction(Request $request, $id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $user = $em->find('OjsUserBundle:User',$id);
        if($user){
            return JsonResponse::create(['id'=>$id,'text'=>$user->getUsername()." <".$user->getEmail().'>']);
        }
        throw new NotFoundHttpException;
    }
    /**
     * @param Request $request
     * @ApiDoc(
     *  resource=true,
     *  description="search journals",
     *  parameters={
     * {
     *          "name"="q",
     *          "dataType"="string",
     *          "required"="true",
     *          "description"="search term"
     *      },
     *      {
     *          "name"="page",
     *          "dataType"="integer",
     *          "required"="false",
     *          "description"="limit"
     *      }
     *  }
     * )
     * @Get("/public/search/journal")
     * @return array
     */
    public function getJournalsAction(Request $request){
        $q = $request->get('q');
        $search = $this->container->get('fos_elastica.index.search.journal');

        $prefix = new Query\Prefix();
        $prefix->setPrefix('title',strtolower($q));
        $qe = new Query();
        $qe->setQuery($prefix);


        $results = $search->search($prefix);
        $data = [];
        foreach ($results as $result) {
            $data[] = [
                'id'=>$result->getId(),
                'text'=>$result->getData()['title'],
            ];
        }
        return $data;
    }
    /**
     * @param $id
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     * @ApiDoc(
     *  resource=true,
     *  description="get journal by id"
     * )
     * @Get("/public/journal/get/{id}")
     */
    public function getJournalAction($id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Journal  $journal */
        $journal = $em->find('OjsJournalBundle:Journal',$id);
        if($journal){
            return JsonResponse::create(['id'=>$id,'text'=>$journal->getTitle() ]);
        }
        throw new NotFoundHttpException;
    }
    
}
