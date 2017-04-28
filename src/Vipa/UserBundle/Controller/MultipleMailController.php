<?php

namespace Vipa\UserBundle\Controller;

use Vipa\CoreBundle\Controller\VipaController as Controller;

use Symfony\Component\HttpFoundation\Request;
use Vipa\UserBundle\Entity\User;
use Vipa\UserBundle\Entity\MultipleMail;
use Vipa\UserBundle\Form\Type\MultipleMailType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Elastica\Exception\NotFoundException;

class MultipleMailController extends Controller
{
    public function multipleMailAction ()
    {

        /** @var User $user */

        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException();
        }

        return $this->render('VipaUserBundle:MultipleMail:multiple_mail.html.twig',array('user' => $user));

    }

    public function addMultipleMailAction (Request $request, $id = null)
    {
        /** @var User $user */
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        if ($id) {
            /** @var MultipleMail $multipleMail */
            $multipleMail = $em->find('VipaUserBundle:MultipleMail', $id);
            if (!$multipleMail) {
                throw new NotFoundException();
            }
            if ($multipleMail->getUserId() != $user->getId()) {
                throw new AccessDeniedException();
            }
        } else {
            $multipleMail = new MultipleMail();
        }

        $multipleMailForm = $this->createForm(new MultipleMailType(), $multipleMail);

        if ($request->isMethod('POST')) {
            $multipleMailForm->handleRequest($request);
            if ($multipleMailForm->isValid()) {

                $multipleMail->setUser($user);
                $em->persist($multipleMail);

                $em->flush();

                return $this->redirectToRoute('vipa_user_multiple_mail');
            } else {
                $this->errorFlashBag('error.oops');
            }
        }

        return $this->render("VipaUserBundle:MultipleMail:add_multiple_mail.html.twig", [
            'form' => $multipleMailForm->createView()
        ]);
    }

    public function deleteMultipleMailAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $multipleMail = $em->find('VipaUserBundle:MultipleMail', $id);
        if (!$multipleMail) {
            throw new NotFoundException();
        }

        $em->remove($multipleMail);
        $em->flush();

        return $this->redirectToRoute('vipa_user_multiple_mail');
    }
}
