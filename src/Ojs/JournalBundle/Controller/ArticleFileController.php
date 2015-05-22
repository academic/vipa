<?php

namespace Ojs\JournalBundle\Controller;

use Ojs\Common\Helper\FileHelper;
use Ojs\Common\Params\ArticleFileParams;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\File;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\ArticleFile;
use Ojs\JournalBundle\Form\ArticleFileType;

/**
 * ArticleFile controller.
 *
 */
class ArticleFileController extends Controller
{

    /**
     * Lists all ArticleFile entities.
     *
     * @param  Article  $article
     * @return Response
     */
    public function indexAction(Article $article)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OjsJournalBundle:ArticleFile')->findByArticle($article);

        return $this->render('OjsJournalBundle:ArticleFile:index.html.twig', array(
            'entities' => $entities,
            'article' => $article,
        ));
    }
    /**
     * Creates a new ArticleFile entity.
     *
     * @param  Request                   $request
     * @param  Article                   $article
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request, Article $article)
    {
        $entity = new ArticleFile();
        $form = $this->createCreateForm($entity, $article);
        $form->handleRequest($request);
        $fileHelper = new FileHelper();
        $em = $this->getDoctrine()->getManager();

        $file_entity = new File();
        if ($form->isValid()) {
            $file = $request->request->get('file');
            $file_entity->setName($file);
            $imagePath = $this->get('kernel')->getRootDir().'/../web/uploads/articlefiles/'.$fileHelper->generatePath($file, false);
            $file_entity->setSize(filesize($imagePath.$file));
            $file_entity->setMimeType(mime_content_type($imagePath.$file));
            $file_entity->setPath('/uploads/articlefiles/'.$fileHelper->generatePath($file, false));
            $em->persist($file_entity);

            $entity->setArticle($article);
            $entity->setFile($file_entity);
            $v = $form->getData()->getVersion();
            if (empty($v)) {
                $entity->setVersion(1);
            }
            $em->persist($entity);
            $article->addArticleFile($entity);
            $em->persist($article);

            $em->flush();

            $this->successFlashBag('Successfully created.');

            return $this->redirect($this->generateUrl('articlefile', array('article' => $article->getId())));
        }

        return $this->render('OjsJournalBundle:ArticleFile:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a ArticleFile entity.
     *
     * @param  ArticleFile $entity
     * @param $article
     * @return Form
     */
    private function createCreateForm(ArticleFile $entity, $article)
    {
        $form = $this->createForm(new ArticleFileType($this->container), $entity, array(
            'action' => $this->generateUrl('articlefile_create', ['article' => $article]),
            'method' => 'POST',
            'user' => $this->getUser(),
        ));

        return $form;
    }

    /**
     * Displays a form to create a new ArticleFile entity.
     *
     * @param  Article  $article
     * @return Response
     */
    public function newAction(Article $article)
    {
        $entity = new ArticleFile();
        $entity->setArticle($article);
        $form   = $this->createCreateForm($entity, $article->getId());

        return $this->render('OjsJournalBundle:ArticleFile:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'article' => $article,
        ));
    }

    /**
     * Finds and displays a ArticleFile entity.
     *
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var ArticleFile $entity */
        $entity = $em->getRepository('OjsJournalBundle:ArticleFile')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $type = ArticleFileParams::fileType($entity->getType());

        return $this->render('OjsJournalBundle:ArticleFile:show.html.twig', array(
            'entity'      => $entity,
            'type' => $type,
        ));
    }

    /**
     * Displays a form to edit an existing ArticleFile entity.
     *
     * @param $id
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var ArticleFile $entity */
        $entity = $em->getRepository('OjsJournalBundle:ArticleFile')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('OjsJournalBundle:ArticleFile:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a ArticleFile entity.
     *
     * @param ArticleFile $entity The entity
     *
     * @return Form The form
     */
    private function createEditForm(ArticleFile $entity)
    {
        $form = $this->createForm(new ArticleFileType($this->container), $entity, array(
            'action' => $this->generateUrl('articlefile_update', array('id' => $entity->getId())),
            'method' => 'POST',
            'user' => $this->getUser(),
        ));

        return $form;
    }
    /**
     * Edits an existing ArticleFile entity.
     *
     * @param  Request                   $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var ArticleFile $entity */
        $entity = $em->getRepository('OjsJournalBundle:ArticleFile')->find($id);
        $file_entity = $entity->getFile();
        if (!$entity) {
            throw $this->createNotFoundException('notFound');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        $fileHelper = new FileHelper();

        if ($editForm->isValid()) {
            $file = $request->request->get('file');
            $file_entity->setName($file);
            $file_entity->setName($file);
            $imagePath = $this->get('kernel')->getRootDir().'/../web/uploads/articlefiles/'.$fileHelper->generatePath($file, false);
            $file_entity->setSize(filesize($imagePath.$file));
            $file_entity->setMimeType(mime_content_type($imagePath.$file));
            $file_entity->setPath('/uploads/articlefiles/'.$fileHelper->generatePath($file, false));
            $em->persist($file_entity);

            $em->flush();

            $this->successFlashBag('Successfully updated.');

            return $this->redirect($this->generateUrl('articlefile_edit', array('id' => $id)));
        }

        return $this->render('OjsJournalBundle:ArticleFile:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
     * Deletes a ArticleFile entity.
     * @param  ArticleFile      $entity
     * @return RedirectResponse
     */
    public function deleteAction(ArticleFile $entity)
    {
        $this->throw404IfNotFound($entity);
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('articlefile', ['article' => $entity->getArticleId()]);
    }
}
