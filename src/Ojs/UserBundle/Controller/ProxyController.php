<?php

namespace Ojs\UserBundle\Controller;

use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\UserBundle\Entity\Proxy;
use Ojs\UserBundle\Entity\User;
use Ojs\UserBundle\Form\Type\ProxyType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Proxy controller.
 *
 */
class ProxyController extends Controller
{
    /**
     * make a user as your proxy
     *
     * @param  Request          $request
     * @param $proxyUserId
     * @return RedirectResponse
     */
    public function giveAction(Request $request, $proxyUserId)
    {
        $url = $request->headers->get("referer");
        $em = $this->getDoctrine()->getManager();
        $currentUser = $this->getUser();
        // check if already assigned
        /** @var User $proxyUser */
        $proxyUser = $this->getDoctrine()->getRepository('OjsUserBundle:User')->find($proxyUserId);
        $check = $this->getDoctrine()->getRepository('OjsUserBundle:Proxy')->findBy(
            array('proxyUserId' => $proxyUserId, 'clientUserId' => $currentUser->getId())
        );
        if ($check) {
            $this->errorFlashBag('Already assigned');

            return new RedirectResponse($url);
        }
        $proxy = new Proxy();
        $proxy->setProxyUser($proxyUser);
        $proxy->setClientUser($currentUser);
        $em->persist($proxy);
        $em->flush();
        $this->successFlashBag('Successfully added as proxy user. This user now can login as you.');
        $this->get('session')->getFlashBag()->add(
            'warning',
            'You can add "end date" for this proxy user. '
            .'<a href="'.$this->generateUrl(
                'user_my_proxy_parents'
            ).'" class="bt btn-sm btn-default">Click</a> to update your proxy users.'
        );

        return new RedirectResponse($url);
    }

    /**
     * @param  Request      $request
     * @param $id
     * @return JsonResponse
     */
    public function updateTtlAction(Request $request, $id)
    {
        /* @var  $proxy Proxy */
        $proxy = $this->getDoctrine()->getRepository('OjsUserBundle:Proxy')->find($id);
        if (!$proxy) {
            throw $this->createNotFoundException('Unable to find Proxy record.');
        }
        $currentUser = $this->getUser();
        if ($proxy->getClientUserId() != $currentUser->getId()) {
            throw $this->createAccessDeniedException('You can not update ttl for this Proxt record.');
        }
        $em = $this->getDoctrine()->getManager();
        $ttl = $request->get('ttl');
        $proxy->setTtl(new \DateTime(date('Y-m-d H:i:s', time() + $ttl * 60 * 60 * 24)));
        $em->persist($proxy);
        $em->flush();

        return new JsonResponse(
            array(
                'id' => $proxy->getId(),
                'ttl' => $proxy->getTtl(),
            )
        );
    }

    /**
     * drop user from your proxy
     *
     * @param  Request          $request
     * @param $proxyUserId
     * @return RedirectResponse
     */
    public function dropAction(Request $request, $proxyUserId)
    {
        $url = $request->headers->get("referer");
        $em = $this->getDoctrine()->getManager();
        $currentUser = $this->getUser();
        // check if already assigned
        $proxyUser = $this->getDoctrine()->getRepository('OjsUserBundle:User')->find($proxyUserId);
        $proxy = $this->getDoctrine()->getRepository('OjsUserBundle:Proxy')->findOneBy(
            array('proxyUserId' => $proxyUser, 'clientUserId' => $currentUser)
        );
        if ($proxy) {
            $em->remove($proxy);
            $em->flush();
        }

        return new RedirectResponse($url);
    }

    /**
     * Lists all Proxy entities.
     *
     */
    public function indexAction()
    {
        if (!$this->isGranted('VIEW', new Proxy())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $source = new Entity('OjsUserBundle:Proxy');
        $grid = $this->get('grid')->setSource($source);
        $gridAction = $this->get('grid_action');
        $actionColumn = new ActionsColumn("actions", "actions");
        $rowAction = [];
        $rowAction[] = $gridAction->showAction('admin_proxy_show', 'id');
        $rowAction[] = $gridAction->editAction('admin_proxy_edit', 'id');
        $rowAction[] = $gridAction->deleteAction('admin_proxy_delete', 'id');
        $actionColumn->setRowActions($rowAction);
        $grid->addColumn($actionColumn);
        $data = [];
        $data['grid'] = $grid;

        return $grid->getGridResponse('OjsUserBundle:Proxy:admin/index.html.twig', $data);
    }

    /**
     * List my proxy clients
     *
     * @param  null                                       $userId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function myProxyClientsAction($userId = null)
    {
        if (!$userId) {
            $userId = $this->getUser()->getId();
        }
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjsUserBundle:Proxy')->findBy(
            array(
                'proxyUserId' => $userId,
            )
        );

        return $this->render(
            'OjsUserBundle:Proxy:clients.html.twig',
            array(
                'entities' => $entities,
            )
        );
    }

    /**
     * List my proxy parents
     *
     * @param  null     $userId
     * @return Response
     */
    public function myProxyParentsAction($userId = null)
    {
        if (!$userId) {
            $userId = $this->getUser()->getId();
        }
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjsUserBundle:Proxy')->findBy(
            array(
                'clientUserId' => $userId,
            )
        );

        return $this->render(
            'OjsUserBundle:Proxy:parents.html.twig',
            array(
                'entities' => $entities,
            )
        );
    }

    /**
     * Creates a new Proxy entity.
     *
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new Proxy())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new Proxy();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.create');

            return $this->redirect($this->generateUrl('admin_proxy_show', array('id' => $entity->getId())));
        }

        return $this->render(
            'OjsUserBundle:Proxy:admin/new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a Proxy entity.
     *
     * @param Proxy $entity The entity
     *
     * @return Form The form
     */
    private function createCreateForm(Proxy $entity)
    {
        $form = $this->createForm(
            new ProxyType($this->container),
            $entity,
            array(
                'action' => $this->generateUrl('admin_proxy_create'),
                'method' => 'POST',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Proxy entity.
     *
     */
    public function newAction()
    {
        if (!$this->isGranted('CREATE', new Proxy())) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $entity = new Proxy();
        $form = $this->createCreateForm($entity);

        return $this->render(
            'OjsUserBundle:Proxy:admin/new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a Proxy entity.
     *
     * @param  Proxy    $entity
     * @return Response
     */
    public function showAction(Proxy $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }

        return $this->render(
            'OjsUserBundle:Proxy:admin/show.html.twig',
            array(
                'entity' => $entity,
            )
        );
    }

    /**
     * Displays a form to edit an existing Proxy entity.
     *
     * @param  Proxy    $entity
     * @return Response
     */
    public function editAction(Proxy $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $editForm = $this->createEditForm($entity);

        return $this->render(
            'OjsUserBundle:Proxy:admin/edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a Proxy entity.
     *
     * @param Proxy $entity The entity
     *
     * @return Form The form
     */
    private function createEditForm(Proxy $entity)
    {
        $form = $this->createForm(
            new ProxyType($this->container),
            $entity,
            array(
                'action' => $this->generateUrl('admin_proxy_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Proxy entity.
     *
     * @param  Request                   $request
     * @param  Proxy                     $entity
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, Proxy $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirect($this->generateUrl('admin_proxy_edit', array('id' => $entity->getId())));
        }

        return $this->render(
            'OjsUserBundle:Proxy:admin/edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a Proxy entity.
     *
     * @param  Proxy            $entity
     * @return RedirectResponse
     */
    public function deleteAction(Proxy $entity)
    {
        $this->throw404IfNotFound($entity);
        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException("You are not authorized for this page!");
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirect($this->generateUrl('admin_proxy'));
    }
}
