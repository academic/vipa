<?php

namespace Ojs\AdminBundle\Controller;

use Ojs\AdminBundle\Entity\SystemSetting;
use Ojs\AdminBundle\Form\Type\SystemSettingsType;
use Ojs\Common\Controller\OjsController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminSystemSettingsController extends OjsController
{
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

    public function indexAction()
    {
        if (!$this->isGranted('VIEW', new SystemSetting())) {
            throw new AccessDeniedException("You cannot see system settings.");
        }

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

    public function updateAction(Request $request)
    {
        if (!$this->isGranted('EDIT', new SystemSetting())) {
            throw new AccessDeniedException("You cannot change system settings.");
        }

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

            $em->flush();
        }

        return $this->redirectToRoute('ojs_admin_system_setting_index');
    }
}
