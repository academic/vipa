<?php

namespace Ojs\AdminBundle\Controller;

use Ojs\AdminBundle\Entity\SystemSetting;
use Ojs\AdminBundle\Form\Type\SystemSettingsType;
use Ojs\CoreBundle\Controller\OjsController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Ojs\AdminBundle\Events\AdminEvent;
use Ojs\AdminBundle\Events\AdminEvents;

class AdminSystemSettingsController extends OjsController
{
    public function indexAction()
    {
        $repo = $this->getDoctrine()->getRepository('OjsAdminBundle:SystemSetting');
        $form = $this->createForm(new SystemSettingsType());

        foreach ($this->getSettings()['boolean'] as $setting) {
            $result = $repo->findOneBy(['name' => $setting]);
            $result ?
                $form->get($setting)->setData($result->getValue()) :
                $form->get($setting)->setData(0);
        }

        return $this->render('OjsAdminBundle:AdminSystemSetting:index.html.twig', ['form' => $form->createView()]);
    }

    private function getSettings()
    {
        return array(
            'boolean' => [
                'user_registration',
                'journal_application',
                'publisher_application',
                'article_submission',
            ]
        );
    }

    public function updateAction(Request $request)
    {
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        $em = $this->getDoctrine()->getManager();
        $repo = $this->getDoctrine()->getRepository('OjsAdminBundle:SystemSetting');
        $form = $this->createForm(new SystemSettingsType());
        $form->handleRequest($request);

        if ($form->isValid()) {
            foreach ($this->getSettings()['boolean'] as $setting) {
                $result = $repo->findOneBy(['name' => $setting]);
                $result ?
                    $result->setValue($form->get($setting)->getData()) :
                    $result = new SystemSetting($setting, $form->get($setting)->getData());
                $em->persist($result);
            }

            $event = new AdminEvent([]);
            $dispatcher->dispatch(AdminEvents::SETTINGS_CHANGE, $event);
            $em->flush();
        }

        return $this->redirectToRoute('ojs_admin_system_setting_index');
    }
}
