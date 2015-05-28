<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Column\NumberColumn;
use APY\DataGridBundle\Grid\Column\TextColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Source\Vector;
use Doctrine\ODM\MongoDB\DocumentManager;
use Ojs\Common\Helper\ActionHelper;
use Ojs\Common\Helper\FileHelper;
use Ojs\JournalBundle\Entity\ArticleFile;
use Ojs\JournalBundle\Entity\Citation;
use Ojs\JournalBundle\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Form\ArticleType;
use Ojs\JournalBundle\Form\CitationType;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\Exception\TokenNotFoundException;

/**
 * Article controller.
 *
 */
class ArticleController extends Controller
{
    /**
     * @param  null|integer $id
     * @return Response
     */
    public function citationAction($id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('OjsJournalBundle:Article')->find($id);
        $post = Request::createFromGlobals();
        if ($post->request->has('cites')) {
        } else {
        }

        return $this->render('OjsJournalBundle:Article:citation.html.twig', array(
            'item' => $article,
            'citationTypes' => $this->container->getParameter('citation_types'),
        ));
    }

    /**
     * Lists all Article entities.
     *
     * @return Response
     */
    public function indexAction()
    {
        $isAdmin = $this->container->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN');
        $source = new Entity('OjsJournalBundle:Article');
        $source->manipulateRow(function (Row $row) {
           if ($row->getField("title") and strlen($row->getField('title'))>20) {
               $row->setField('title', substr($row->getField('title'), 0, 20)."...");
           }

            return $row;
        });
        $tableAlias = $source->getTableAlias();
        $selectedJournalId = $this->get('session')->get("selectedJournalId");
        //if user not admin show only selected journal articles
        if(!$isAdmin){
            $source->manipulateQuery(
                function ($query) use ($tableAlias, $selectedJournalId)
                {
                    $query->andWhere($tableAlias . '.journalId = :selectedJournalId')
                    ->setParameter('selectedJournalId', $selectedJournalId);
                }
            );
        }
        $grid = $this->get('grid')->setSource($source);

        $actionColumn = new ActionsColumn("actions", 'actions');
        ActionHelper::setup($this->get('security.csrf.token_manager'));
        $rowAction[] = ActionHelper::showAction('article_show', 'id');
        $rowAction[] = ActionHelper::editAction('article_edit', 'id');
        $rowAction[] = ActionHelper::deleteAction('article_delete', 'id');
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsJournalBundle:Article:index.html.twig', $data);
    }

    /**
     * Lists all Article entities for journal
     *
     * @param  integer  $journalId
     * @return Response
     */
    public function indexJournalAction($journalId)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($journalId);
        $this->throw404IfNotFound($journal);
        $entities = $em->getRepository('OjsJournalBundle:Article')->findByJournalId($journalId);

        return $this->render('OjsJournalBundle:Article:index_journal.html.twig', array(
            'entities' => $entities,
            'journal' => $journal,
        ));
    }

    /**
     * Lists all Article entities for issue
     *
     * @param  integer  $issueId
     * @return Response
     */
    public function indexIssueAction($issueId)
    {
        $em = $this->getDoctrine()->getManager();
        $issue = $em->getRepository('OjsJournalBundle:Issue')->find($issueId);
        $this->throw404IfNotFound($issue);
        $entities = $em->getRepository('OjsJournalBundle:Article')->findByIssueId($issueId);

        return $this->render('OjsJournalBundle:Article:index_issue.html.twig', array(
            'entities' => $entities,
            'issue' => $issue,
        ));
    }

    /**
     * Creates a new Article entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Article();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $header = $request->request->get('header');
            if ($header) {
                $entity->setHeaderOptions(json_encode($header));
            }
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('articlefile', [
                'article' => $entity->getId(),
                ]
            );
        }

        return $this->render('OjsJournalBundle:Article:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Article entity.
     *
     * @param Article $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Article $entity)
    {
        $journal = $this->get('session')->get("selectedJournalId");
        $form = $this->createForm(
            new ArticleType(), $entity, array(
                'action' => $this->generateUrl('article_create'),
                'method' => 'POST',
                'journal' => $journal,
                'user' => $this->getUser(),
            ));

        return $form;
    }

    /**
     * Displays a form to create a new Article entity.
     *
     */
    public function newAction()
    {
        $entity = new Article();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:Article:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays an Article entity for admin user
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /* @var $entity \Ojs\JournalBundle\Entity\Article */
        $entity = $em->getRepository('OjsJournalBundle:Article')->find($id);
        $this->throw404IfNotFound($entity);

        return $this->render('OjsJournalBundle:Article:show.html.twig', array(
            'entity' => $entity, ));
    }

    /**
     * Display an Article entity as author preview
     * @param integer $id
     * @return Response
     */
    public function previewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /* @var $entity \Ojs\JournalBundle\Entity\Article */
        $entity = $em->getRepository('OjsJournalBundle:Article')->find($id);
        $this->throw404IfNotFound($entity);

        return $this->render('OjsJournalBundle:Article:author_preview.html.twig', array(
            'entity' => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing Article entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Article')->find($id);
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);

        return $this->render('OjsJournalBundle:Article:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Article entity.
     *
     * @param Article $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Article $entity)
    {
        $journal = $this->get('session')->get("selectedJournalId");

        $form = $this->createForm(new ArticleType(), $entity, array(
            'action' => $this->generateUrl('article_update', array('id' => $entity->getId())),
            'method' => 'POST',
            'journal' => $journal,
            'user' => $this->getUser(),
        ));

        return $form;
    }

    /**
     * Edits an existing Article entity.
     *
     * @param  Request                   $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Article $entity */
        $entity = $em->getRepository('OjsJournalBundle:Article')->find($id);
        /** @var DocumentManager $dm */
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $this->throw404IfNotFound($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $header = $request->request->get('header');
            if ($header) {
                $entity->setHeaderOptions(json_encode($header));
            }

            $files = $request->request->get('articlefiles', []);
            $fileHelper = new FileHelper();

            foreach ($files as $file) {
                $file_entity = new File();
                $file_entity->setName($file);
                $imagepath = $this->get('kernel')->getRootDir().'/../web/uploads/articlefiles/'.$fileHelper->generatePath($file, false);
                $file_entity->setSize(filesize($imagepath.$file));
                $file_entity->setMimeType(mime_content_type($imagepath.$file));
                $file_entity->setPath('/uploads/articlefiles/'.$fileHelper->generatePath($file, false));
                $em->persist($file_entity);
                $articleFile = new ArticleFile();
                $articleFile->setArticle($entity);
                $articleFile->setFile($file_entity);
                //ArticleFileParams::$FILE_TYPES
                $articleFile->setType(0);
                $articleFile->setVersion(1);
                $articleFile->setLangCode('tr');//mock
                $em->persist($articleFile);
                $entity->addArticleFile($articleFile);
            }

            $dm->flush();
            $em->flush();

            $this->successFlashBag('successful.update');

            return $this->redirect($this->generateUrl('article_edit', array('id' => $id)));
        }

        return $this->render('OjsJournalBundle:Article:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Article')->find($id);
        $this->throw404IfNotFound($entity);

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('article'.$id);
        if($token!=$request->get('_token'))
            throw new TokenNotFoundException("Token Not Found!");

        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirect($this->generateUrl('article'));
    }

    /**
     * @param $id
     * @return Response
     */
    public function articleCitationsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Article $article */
        $article = $em->find('OjsJournalBundle:Article', $id);
        $data['entity'] = $article;

        $citations = [];
        foreach ($article->getCitations() as $citation) {
            $citations[] = [
                'id' => $citation->getId(),
                'raw' => $citation->getRaw(),
            ];
        }

        $source = new Vector($citations);
        $grid = $this->get('grid')->setSource($source);

        $columns = [
            new NumberColumn(["id" => "id", "field" => "id", "primary" => true, "title" => "ID"]),
            new TextColumn(["id" => "raw", "field" => "raw", "title" => "Citation"]),
        ];

        foreach ($columns as $column) {
            $grid->addColumn($column);
        }

        $actionsColumn = new ActionsColumn("actions", 'actions');
        ActionHelper::setup($this->get('security.csrf.token_manager'));
        $actions[] = ActionHelper::showAction('citation_show', 'id');
        $actions[] = ActionHelper::editAction('citation_edit', 'id');
        $actions[] = ActionHelper::deleteAction('citation_delete', 'id');
        $actionsColumn->setRowActions($actions);
        $grid->addColumn($actionsColumn);

        $data['id'] = $id;
        $data['grid'] = $grid;

        return $grid->getGridResponse('@OjsJournal/Article/citations.html.twig', $data);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function articleNewCitationAction($id)
    {
        $citationEntity = new Citation();
        $articleEntity = $this->getDoctrine()->getRepository('OjsJournalBundle:Article')->find($id);

        $form = $this->createForm(new CitationType(), $citationEntity, array(
            'action' => $this->generateUrl('article_citations_create', ['id' => $id]),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr'=> ['class' => 'btn btn-success pull-right']));

        $data = ['form' => $form->createView(), 'entity' => $articleEntity];
        return $this->render('OjsJournalBundle:Article:new_citation.html.twig', $data);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function articleCreateCitationAction(Request $request, $id)
    {
        $citationEntity = new Citation();
        /** @var Article $articleEntity */
        $articleEntity = $this->getDoctrine()->getRepository('OjsJournalBundle:Article')->find($id);
        $form = $this->createForm(new CitationType(), $citationEntity, array(
            'action' => $this->generateUrl('article_citations_create', ['id' => $id]),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr'=> ['class' => 'btn btn-success pull-right']));
        $form->handleRequest($request);

        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($citationEntity);
            $em->flush();

            $articleEntity->addCitation($citationEntity);
            $em->persist($articleEntity);
            $em->flush();

            return $this->redirectToRoute('article_citations', ['id' => $id]);
        }

        $data = ['form' => $form->createView(), 'entity' => $articleEntity];
        return $this->render('OjsJournalBundle:Article:new_citation.html.twig', $data);
    }
}
