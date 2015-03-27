<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ODM\MongoDB\DocumentManager;
use Ojs\Common\Helper\ActionHelper;
use Ojs\JournalBundle\Entity\ArticleFile;
use Ojs\JournalBundle\Entity\File;
use Ojs\SiteBundle\Document\ImageOptions;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Form\ArticleType;

/**
 * Article controller.
 *
 */
class ArticleController extends Controller
{

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
            'citationTypes' => $this->container->getParameter('citation_types')
        ));
    }

    /**
     * Lists all Article entities.
     *
     */
    public function indexAction()
    {
        $source = new Entity('OjsJournalBundle:Article');
        $source->manipulateRow(function(Row $row){
           if($row->getField("title") and strlen($row->getField('title'))>20){
               $row->setField('title',substr($row->getField('title'),0,20)."...");
           }
            return $row;
        });
        $grid = $this->get('grid')->setSource($source);

        $actionColumn = new ActionsColumn("actions", 'actions');
        $rowAction[] = ActionHelper::showAction('article_show', 'id');
        $rowAction[] = ActionHelper::editAction('article_edit', 'id');
        $rowAction[] = ActionHelper::deleteAction('article_delete', 'id');
        $rowAction[] = ActionHelper::userAnonymLoginAction();

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        $data = [];
        $data['grid'] = $grid;
        return $grid->getGridResponse('OjsJournalBundle:Article:index.html.twig', $data);
    }

    /**
     * Lists all Article entities for journal
     * @param integer $journalId
     */
    public function indexJournalAction($journalId)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($journalId);
        $this->throw404IfNotFound($journal);
        $entities = $em->getRepository('OjsJournalBundle:Article')->findByJournalId($journalId);

        return $this->render('OjsJournalBundle:Article:index_journal.html.twig', array(
            'entities' => $entities,
            'journal' => $journal
        ));
    }

    /**
     * Lists all Article entities for issue
     * @param integer $journalId
     */
    public function indexIssueAction($issueId)
    {
        $em = $this->getDoctrine()->getManager();
        $issue = $em->getRepository('OjsJournalBundle:Issue')->find($issueId);
        $this->throw404IfNotFound($issue);
        $entities = $em->getRepository('OjsJournalBundle:Article')->findByIssueId($issueId);

        return $this->render('OjsJournalBundle:Article:index_issue.html.twig', array(
            'entities' => $entities,
            'issue' => $issue
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
            if($header){
                $entity->setHeaderOptions(json_encode($header));
            }
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('articlefile', array('article' => $entity->getId())));
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
                'journal' => $journal
            ,
                'user' => $this->getUser()
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
            'entity' => $entity));
    }

    /**
     * Display an Article entity as author preview
     * @param integer $id
     */
    public function previewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /* @var $entity \Ojs\JournalBundle\Entity\Article */
        $entity = $em->getRepository('OjsJournalBundle:Article')->find($id);
        $this->throw404IfNotFound($entity);

        return $this->render('OjsJournalBundle:Article:author_preview.html.twig', array(
            'entity' => $entity
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
            'user' => $this->getUser()
        ));

        return $form;
    }

    /**
     * Edits an existing Article entity.
     *
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
            if($header){
                $entity->setHeaderOptions(json_encode($header));
            }

            $files = $request->request->get('articlefiles', []);
            $fileHelper = new \Ojs\Common\Helper\FileHelper();

            foreach ($files as $file) {
                $file_entity = new File();
                $file_entity->setName($file);
                $imagepath = $this->get('kernel')->getRootDir() . '/../web/uploads/articlefiles/' . $fileHelper->generatePath($file, false);
                $file_entity->setSize(filesize($imagepath . $file));
                $file_entity->setMimeType(mime_content_type($imagepath . $file));
                $file_entity->setPath('/uploads/articlefiles/' . $fileHelper->generatePath($file, false));
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

            return $this->redirect($this->generateUrl('article_edit', array('id' => $id)));
        }

        return $this->render('OjsJournalBundle:Article:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Deletes a Article entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsJournalBundle:Article')->find($id);
        $this->throw404IfNotFound($entity);
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('article'));
    }

}
