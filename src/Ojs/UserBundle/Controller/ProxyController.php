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
     * @param  Request $request
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
            . '<a href="' . $this->generateUrl(
                'user_my_proxy_parents'
            ) . '" class="bt btn-sm btn-default">Click</a> to update your proxy users.'
        );

        return new RedirectResponse($url);
    }

    /**
     * @param  Request $request
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
     * @param  Request $request
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
     * List my proxy clients
     *
     * @param  null $userId
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
     * @param  null $userId
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
}
