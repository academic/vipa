<?php

namespace Ojs\JournalBundle\Controller;

use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
     */
    public function indexAction(Article $article)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OjsJournalBundle:ArticleFile')->findByArticle($article);

        return $this->render('OjsJournalBundle:ArticleFile:index.html.twig', array(
            'entities' => $entities,
            'article'=>$article
        ));
    }
    /**
     * Creates a new ArticleFile entity.
     *
     */
    public function createAction(Request $request, Article $article)
    {
        $entity = new ArticleFile();
        $form = $this->createCreateForm($entity,$article);
        $form->handleRequest($request);
        $fileHelper = new \Ojs\Common\Helper\FileHelper();
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $file_entity = new File();
        if ($form->isValid()) {
            $file = $request->request->get('file');
            $file_entity->setName($file);
            $imagepath = $this->get('kernel')->getRootDir() . '/../web/uploads/articlefiles/' . $fileHelper->generatePath($file, false);
            $file_entity->setSize(filesize($imagepath.$file));
            $file_entity->setMimeType(mime_content_type($imagepath.$file));
            $file_entity->setPath('/uploads/articlefiles/' . $fileHelper->generatePath($file, false));
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
     * @param ArticleFile $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ArticleFile $entity,$article)
    {
        $form = $this->createForm(new ArticleFileType(), $entity, array(
            'action' => $this->generateUrl('articlefile_create',['article'=> $article]),
            'method' => 'POST',
            'user'=>$this->getUser()
        ));


        return $form;
    }

    /**
     * Displays a form to create a new ArticleFile entity.
     *
     */
    public function newAction(Article $article)
    {
        $entity = new ArticleFile();
        $form   = $this->createCreateForm($entity,$article->getId());

        return $this->render('OjsJournalBundle:ArticleFile:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'article'=> $article
        ));
    }

    /**
     * Finds and displays a ArticleFile entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:ArticleFile')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticleFile entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjsJournalBundle:ArticleFile:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ArticleFile entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjsJournalBundle:ArticleFile')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticleFile entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OjsJournalBundle:ArticleFile:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a ArticleFile entity.
    *
    * @param ArticleFile $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ArticleFile $entity)
    {
        $form = $this->createForm(new ArticleFileType(), $entity, array(
            'action' => $this->generateUrl('articlefile_update', array('id' => $entity->getId())),
            'method' => 'POST',
            'user'=>$this->getUser()
        ));


        return $form;
    }
    /**
     * Edits an existing ArticleFile entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var ArticleFile $entity */
        $entity = $em->getRepository('OjsJournalBundle:ArticleFile')->find($id);
        $file_entity = $entity->getFile();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticleFile entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        $fileHelper = new \Ojs\Common\Helper\FileHelper();

        if ($editForm->isValid()) {
            $file = $request->request->get('file');
            $file_entity->setName($file);
            $file_entity->setName($file);
            $imagepath = $this->get('kernel')->getRootDir() . '/../web/uploads/articlefiles/' . $fileHelper->generatePath($file, false);
            $file_entity->setSize(filesize($imagepath.$file));
            $file_entity->setMimeType(mime_content_type($imagepath.$file));
            $file_entity->setPath('/uploads/articlefiles/' . $fileHelper->generatePath($file, false));
            $em->persist($file_entity);

            $em->flush();

            return $this->redirect($this->generateUrl('articlefile_edit', array('id' => $id)));
        }

        return $this->render('OjsJournalBundle:ArticleFile:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a ArticleFile entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var ArticleFile $entity */
        $entity = $em->getRepository('OjsJournalBundle:ArticleFile')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticleFile entity.');
        }
        $articleid = $entity->getArticleId();

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('articlefile',['article'=>$articleid]));
    }

    /**
     * Creates a form to delete a ArticleFile entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('articlefile_delete', array('id' => $id)))
            ->setMethod('GET')
            ->getForm()
        ;
    }
}
