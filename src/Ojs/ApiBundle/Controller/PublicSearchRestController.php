<?php

namespace Ojs\ApiBundle\Controller;

use Doctrine\ORM\EntityManager;
use Elastica\Query;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojs\JournalBundle\Entity\Citation;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\Publisher;
use OkulBilisim\LocationBundle\Entity\Province;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * PublicSearchRest may contain similar actions with SearchRest
 */
class PublicSearchRestController extends FOSRestController
{

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="search Publishers",
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
     * @Get("/public/search/publisher")
     *
     * @param  Request $request
     * @return array
     */
    public function getPublishersAction(Request $request)
    {
        #$limit = $request->get('limit');
        #$verified = $request->get('verified');
        $q = $request->get('q');
        $search = $this->container->get('fos_elastica.index.search.publisher');

        $prefix = new Query\Prefix();
        $prefix->setPrefix('name', strtolower($q));
        $qe = new Query();
        $qe->setQuery($prefix);

        $results = $search->search($prefix);
        $data = [];
        foreach ($results as $result) {
            $data[] = array_merge(array('id' => $result->getId()), $result->getData());
        }

        return $data;
    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="search Publishers for autocomplete",
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
     * @Get("/public/search/institute")
     *
     * @param  Request $request
     * @return array
     */
    public function getInstitutesAction(Request $request)
    {
        $q = $request->get('q');
        $search = $this->container->get('fos_elastica.index.search.publisher');

        $prefix = new Query\Prefix();
        $prefix->setPrefix('name', strtolower($q));
        $qe = new Query();
        $qe->setQuery($prefix);

        $results = $search->search($prefix);

        $data = [];
        foreach ($results as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => $result->getData()['name'],
            ];
        }

        return $data;
    }

    /**
     * @param $id
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @ApiDoc(
     *                                    resource=true,
     *                                    description="get publisher by id"
     *                                    )
     * @Get("/public/publisher/get/{id}", defaults={"id" = null})
     */
    public function getPublisherAction($id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Publisher $publisher */
        $publisher = $em->find('OjsJournalBundle:Publisher', $id);
        if ($publisher) {
            return JsonResponse::create(['id' => $id, 'text' => $publisher->getName()]);
        }
        throw new NotFoundHttpException();
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="search Publishers",
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
     *
     * @param  Request $request
     * @return array
     */
    public function getTagsAction(Request $request)
    {
        #$limit = 12;
        $q = $request->get('q');
        $search = $this->container->get('fos_elastica.index.search');

        $prefix = new Query\Prefix();
        $prefix->setPrefix('tags', strtolower($q));
        $qe = new Query();
        $qe->setQuery($prefix);

        $results = $search->search($prefix);
        $data = [];
        foreach ($results as $result) {
            foreach (explode(',', $result->getData()['tags']) as $tag) {
                $data[] = ['id' => $tag, 'text' => $tag];
            }
        }

        return $data;
    }

    /**
     * @param  Request $request
     * @ApiDoc(
     *                          resource=true,
     *                          description="search users",
     *                          parameters={
     *                          {
     *                          "name"="q",
     *                          "dataType"="string",
     *                          "required"="true",
     *                          "description"="search term"
     *                          },
     *                          {
     *                          "name"="page",
     *                          "dataType"="integer",
     *                          "required"="false",
     *                          "description"="limit"
     *                          }
     *                          }
     *                          )
     * @Get("/public/search/user")
     * @return array
     */
    public function getUsersAction(Request $request)
    {
        #$limit = 12;
        $q = $request->get('q');
        $search = $this->container->get('fos_elastica.index.search.user');

        $prefix = new Query\Prefix();
        $prefix->setPrefix('username', strtolower($q));
        $qe = new Query();
        $qe->setQuery($prefix);

        $results = $search->search($prefix);
        $data = [];
        foreach ($results as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => $result->getData()['username']." - ".$result->getData()['email'],
            ];
        }

        return $data;
    }

    /**
     * @param  Request $request
     * @ApiDoc(
     *                          resource=true,
     *                          description="search journals",
     *                          parameters={
     *                          {
     *                          "name"="q",
     *                          "dataType"="string",
     *                          "required"="true",
     *                          "description"="search term"
     *                          },
     *                          {
     *                          "name"="page",
     *                          "dataType"="integer",
     *                          "required"="false",
     *                          "description"="limit"
     *                          }
     *                          }
     *                          )
     * @Get("/public/search/journal")
     * @return array
     */
    public function getJournalsAction(Request $request)
    {
        $q = $request->get('q');

        $search = $this->container->get('fos_elastica.index.search.journal');
        $match = new Query\Match();
        $match->setField('title', $q);
        $results = $search->search($match);

        $data = [];
        foreach ($results as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => $result->getData()['title'],
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
     *                                                    resource=true,
     *                                                    description="get journal by id"
     *                                                    )
     * @Get("/public/journal/get/{id}")
     */
    public function getJournalAction($id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Journal $journal */
        $journal = $em->find('OjsJournalBundle:Journal', $id);
        if ($journal) {
            return JsonResponse::create(['id' => $id, 'text' => $journal->getTitle()]);
        }
        throw new NotFoundHttpException();
    }

    /**
     * @param  Request $request
     * @ApiDoc(
     *                          resource=true,
     *                          description="search citation",
     *                          parameters={
     *                          {
     *                          "name"="q",
     *                          "dataType"="string",
     *                          "required"="true",
     *                          "description"="search term"
     *                          },
     *                          {
     *                          "name"="page",
     *                          "dataType"="integer",
     *                          "required"="false",
     *                          "description"="limit"
     *                          }
     *                          }
     *                          )
     * @Get("/public/search/citation")
     * @return array
     */
    public function getCitationsAction(Request $request)
    {
        $q = $request->get('q');
        $search = $this->container->get('fos_elastica.index.search.citation');

        $prefix = new Query\Prefix();
        $prefix->setPrefix('raw', strtolower($q));
        $qe = new Query();
        $qe->setQuery($prefix);

        $results = $search->search($prefix);
        $data = [];
        foreach ($results as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => $result->getData()['raw'],
            ];
        }

        return $data;
    }

    /**
     * @param  Request $request
     * @ApiDoc(
     *                          resource=true,
     *                          description="search articles",
     *                          parameters={
     *                          {
     *                          "name"="q",
     *                          "dataType"="string",
     *                          "required"="true",
     *                          "description"="search term"
     *                          },
     *                          {
     *                          "name"="page",
     *                          "dataType"="integer",
     *                          "required"="false",
     *                          "description"="limit"
     *                          }
     *                          }
     *                          )
     * @Get("/public/search/article")
     * @return array
     */
    public function getArticlesAction(Request $request)
    {
        $q = $request->get('q');
        $search = $this->container->get('fos_elastica.index.search.articles');

        $prefix = new Query\Prefix();
        $prefix->setPrefix('title', strtolower($q));
        $qe = new Query();
        $qe->setQuery($prefix);

        $results = $search->search($prefix);
        $data = [];
/** @var User $user */
        # $user = $this->getUser();

        #$articleRepo = $this->getDoctrine()->getManager()->getRepository('OjsJournalBundle:Article');
        foreach ($results as $result) {
            /*   if($user->isAdmin())
            {*/
            $data[] = [
                'id' => $result->getId(),
                'text' => $result->getData()['title'],
            ];
            /* }elseif($user->hasRole('ROLE_JOURNAL_MANAGER') || $user->hasRole('ROLE_EDITOR')){
                 /** @var Article $article
                 $article = $articleRepo->find($result->getId());
                 $roles = $article->getJournal()->getUserRoles();
                 foreach ($roles as $role) {
                     /** @var UserJournalRole $role
                     if($role->getRole()->getRole()=='ROLE_JOURNAL_MANAGER' || $role->getRole()->getRole()=='ROLE_EDITOR'){
                         $data[] = [
                             'id'=>$result->getId(),
                             'text'=>$result->getData()['title'],
                         ];
                         break;
                     }
                 }
             }*/
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
     *                                                    resource=true,
     *                                                    description="get article by id"
     *                                                    )
     * @Get("/public/article/get/{id}")
     */
    public function getArticleAction($id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Journal $journal */
        $article = $em->find('OjsJournalBundle:Article', $id);
        $data = [
            'id' => $article->getId(),
            'text' => $article->getTitle(),
        ];
        if ($data) {
            return JsonResponse::create($data);
        }
        throw new NotFoundHttpException();
    }
}
