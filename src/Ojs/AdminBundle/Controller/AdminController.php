<?php

namespace Ojs\AdminBundle\Controller;

use Ojs\AdminBundle\Form\Type\QuickSwitchType;
use Ojs\AnalyticsBundle\Utils\GraphDataGenerator;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminController extends Controller
{
    /**
     * @return RedirectResponse
     */
    public function dashboardCheckAction()
    {
        if ($this->getUser()) {
            if ($this->getUser()->isAdmin()) {
                return $this->redirect($this->generateUrl('ojs_admin_dashboard'));
            } else {
                return $this->redirect($this->generateUrl('ojs_user_index'));
            }
        } else {
            throw new AccessDeniedException('You are not allowed to see this page');
        }
    }

    /**
     * @return RedirectResponse|Response
     */
    public function dashboardAction()
    {
        if ($this->isGranted('VIEW', new Journal())) {
            $switcher = $this->createForm(new QuickSwitchType(), null)->createView();

            return $this->render('OjsAdminBundle:Admin:dashboard.html.twig', [
                'switcher' => $switcher,
                'unreadFeedbacks' => $this->getUnreadFeedbackCount()
                ]
            );
        } else {
            return $this->redirect($this->generateUrl('ojs_user_index'));
        }
    }

    private function getUnreadFeedbackCount()
    {
        $em = $this->getDoctrine()->getManager();
        $unreadFeedbacks = $em->getRepository('OkulBilisimFeedbackBundle:Feedback')->findBy([
            'status' => 0,
            'deleted' => false
        ]);
        return count($unreadFeedbacks);
    }

    /**
     * @return RedirectResponse|Response
     */
    public function statsAction()
    {
        if (!$this->isGranted('VIEW', new Journal())) {
            throw new AccessDeniedException("You not authorized for this page!");
        }
        $cache = $this->get('file_cache');
        if(!$cache->contains('admin_statics')){
            return new Response('page.not.available.for.now');
        }
        return $this->render('OjsAdminBundle:Admin:stats.html.twig', $cache->fetch('admin_statics'));
    }
}
