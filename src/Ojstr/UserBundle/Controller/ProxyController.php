<?php

namespace Ojstr\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojstr\UserBundle\Entity\Proxy;
use Ojstr\UserBundle\Form\ProxyType;

/**
 * Proxy controller.
 *
 */
class ProxyController extends Controller {

    /**
     * make a user as your proxy
     *
     */
    public function giveAction($proxyUserId) {
        $url = $this->getRequest()->headers->get("referer");
        $em = $this->getDoctrine()->getManager();
        $currentUser = $this->getUser();
        // check if already assigned
        $proxyUser = $this->getDoctrine()->getRepository('OjstrUserBundle:User')->find($proxyUserId);
        $check = $this->getDoctrine()->getRepository('OjstrUserBundle:Proxy')->findBy(
                array('proxyUserId' => $proxyUserId, 'clientUserId' => $currentUser->getId())
        );
        if ($check) {
            $this->get('session')->getFlashBag()->add('error', 'Already assigned');
            return new \Symfony\Component\HttpFoundation\RedirectResponse($url);
        }
        $proxy = new Proxy();
        $proxy->setProxyUser($proxyUser);
        $proxy->setClientUser($currentUser);
        $em->persist($proxy);
        $em->flush();
        $this->get('session')->getFlashBag()->add('success', 'Successfully added as proxy user. This user now can login as you. ');
        $this->get('session')->getFlashBag()->add('warning', 'You can add "end date" for this proxy user. '
                . '<a href="' . $this->generateUrl('user_my_proxy_parents') . '" class="bt btn-sm btn-default">Click</a> to update your proxy users.');
        return new \Symfony\Component\HttpFoundation\RedirectResponse($url);
    }

    public function updateTtlAction(Request $request, $id) {
        /* @var  $proxy Proxy */
        $proxy = $this->getDoctrine()->getRepository('OjstrUserBundle:Proxy')->find($id);
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
        return new \Symfony\Component\HttpFoundation\JsonResponse(
                array(
            'id' => $proxy->getId(),
            'ttl' => $proxy->getTtl())
        );
    }

    /**
     * drop user from your proxy
     *
     */
    public function dropAction($proxyUserId) {
        $url = $this->getRequest()->headers->get("referer");
        $em = $this->getDoctrine()->getManager();
        $currentUser = $this->getUser();
        // check if already assigned
        $proxyUser = $this->getDoctrine()->getRepository('OjstrUserBundle:User')->find($proxyUserId);
        $proxy = $this->getDoctrine()->getRepository('OjstrUserBundle:Proxy')->findOneBy(
                array('proxyUserId' => $proxyUser, 'clientUserId' => $currentUser)
        );
        if ($proxy) {
            $em->remove($proxy);
            $em->flush();
        }
        return new \Symfony\Component\HttpFoundation\RedirectResponse($url);
    }

    /**
     * Lists all Proxy entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjstrUserBundle:Proxy')->findAll();
        return $this->render('OjstrUserBundle:Proxy:admin/index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * List my proxy clients
     * @param $userId int
     */
    public function myProxyClientsAction($userId = NULL) {
        if (!$userId) {
            $userId = $this->getUser()->getId();
        }
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjstrUserBundle:Proxy')->findBy(array(
            'proxyUserId' => $userId
        ));
        return $this->render('OjstrUserBundle:Proxy:clients.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * List my proxy parents
     * @param $userId int
     */
    public function myProxyParentsAction($userId = NULL) {
        if (!$userId) {
            $userId = $this->getUser()->getId();
        }
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('OjstrUserBundle:Proxy')->findBy(array(
            'clientUserId' => $userId
        ));
        return $this->render('OjstrUserBundle:Proxy:parents.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new Proxy entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Proxy();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_proxy_show', array('id' => $entity->getId())));
        }

        return $this->render('OjstrUserBundle:Proxy:admin/new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Proxy entity.
     *
     * @param Proxy $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Proxy $entity) {
        $form = $this->createForm(new ProxyType(), $entity, array(
            'action' => $this->generateUrl('admin_proxy_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Proxy entity.
     *
     */
    public function newAction() {
        $entity = new Proxy();
        $form = $this->createCreateForm($entity);

        return $this->render('OjstrUserBundle:Proxy:admin/new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Proxy entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrUserBundle:Proxy')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Proxy entity.');
        }
        return $this->render('OjstrUserBundle:Proxy:admin/show.html.twig', array(
                    'entity' => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing Proxy entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrUserBundle:Proxy')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Proxy entity.');
        }

        $editForm = $this->createEditForm($entity);
        return $this->render('OjstrUserBundle:Proxy:admin/edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Proxy entity.
     *
     * @param Proxy $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Proxy $entity) {
        $form = $this->createForm(new ProxyType(), $entity, array(
            'action' => $this->generateUrl('admin_proxy_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Proxy entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OjstrUserBundle:Proxy')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Proxy entity.');
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_proxy_edit', array('id' => $id)));
        }
        return $this->render('OjstrUserBundle:Proxy:admin/edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Proxy entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjstrUserBundle:Proxy')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Proxy entity.');
        }
        $em->remove($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('admin_proxy'));
    }

}
