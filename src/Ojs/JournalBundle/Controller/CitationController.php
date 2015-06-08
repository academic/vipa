<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Row;
use Ojs\JournalBundle\Entity\Article;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Citation;
use Ojs\JournalBundle\Form\CitationType;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Helper\ActionHelper;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Citation controller.
 *
 */
class CitationController extends Controller
{
    /**
     * Lists all Citation entities.
     *
     */
    public function indexAction()
    {
        if(!$this->isGranted('VIEW', new Citation())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $source = new Entity('OjsJournalBundle:Citation');
        $router = $this->get('router');
        $source->manipulateRow(function (Row $row) use ($router) {
           if ($row->getField('id')) {
               /** @var Citation $entity */
               $entity = $row->getEntity();
               $articles = $entity->getArticles();
               $a = [];
               foreach ($articles as $article) {
                   /** @var Article $article */
                   $route = $router->generate('article_edit', ['id' => $article->getId()]);
                   $a[] = "<a href='{$route}' class='badge' title='{$article->getTitle()}'>{$article->getId()}</a>";
               }
               $row->setField('articles', implode(' ', $a));
           }

            return $row;
        });
        $grid = $this->get('grid')->setSource($source);
        $actionColumn = new ActionsColumn("actions", 'actions');
        ActionHelper::setup($this->get('security.csrf.token_manager'), $this->get('translator'));

        $rowAction[] = ActionHelper::showAction('citation_show', 'id');
        $rowAction[] = ActionHelper::editAction('citation_edit', 'id');
        $rowAction[] = ActionHelper::deleteAction('citation_delete', 'id');

        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);

        return $grid->getGridResponse('OjsJournalBundle:Citation:index.html.twig', array('grid' => $grid));
    }

    /**
     * Creates a new Citation entity.
     *
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        if(!$this->isGranted('CREATE', new Citation())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new Citation();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirectToRoute('citation_show', ['id' => $entity->getId()]);
        }

        return $this->render('OjsJournalBundle:Citation:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Citation entity.
     *
     * @param Citation $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Citation $entity)
    {
        $form = $this->createForm(new CitationType(), $entity, array(
            'action' => $this->generateUrl('citation_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Citation entity.
     *
     */
    public function newAction()
    {
        if(!$this->isGranted('CREATE', new Citation())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new Citation();
        $form = $this->createCreateForm($entity);

        return $this->render('OjsJournalBundle:Citation:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Citation for an Article
     *
     */
    public function articleAction($article_id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $article = !empty($article_id) ?
                $em->getRepository('OjsJournalBundle:Article')->find($article_id) :
                null;
        $entity = new Citation();
        $entity->addArticle($article);
        $form = $this->createForm(new \Ojs\JournalBundle\Form\ArticleCitationType(), $entity);

        return $this->render('OjsJournalBundle:Citation:article.html.twig', array(
                    'entity' => $entity,
                    'article' => $article,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Citation entity.
     *
     * @param Citation $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Citation $entity)
    {
        $this->throw404IfNotFound($entity);
        if(!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        return $this->render('OjsJournalBundle:Citation:show.html.twig', array(
                    'entity' => $entity
            )
        );
    }

    /**
     * Displays a form to edit an existing Citation entity.
     *
     * @param Citation $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Citation $entity)
    {
        $this->throw404IfNotFound($entity);
        if(!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $editForm = $this->createEditForm($entity);
        return $this->render('OjsJournalBundle:Citation:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView()
            )
        );
    }

    /**
     * Creates a form to edit a Citation entity.
     *
     * @param  Citation $entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Citation $entity)
    {
        $form = $this->createForm(new CitationType(), $entity, array(
            'action' => $this->generateUrl('citation_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Citation entity.
     *
     * @param Request $request
     * @param Citation $entity
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, Citation $entity)
    {
        $this->throw404IfNotFound($entity);
        if(!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('citation_edit', ['id' => $entity->getId()]);
        }

        return $this->render('OjsJournalBundle:Citation:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Citation entity.
     *
     * @param $request
     * @param Citation  $entity
     * @return RedirectResponse
     */
    public function deleteAction(Request $request,Citation $entity)
    {
        $this->throw404IfNotFound($entity);
        if(!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $em = $this->getDoctrine()->getManager();
        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->getToken('citation'.$entity->getId());
        if($token!=$request->get('_token'))
            throw new TokenNotFoundException("Token Not Found!");
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('citation');
    }
}
