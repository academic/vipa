<?php

namespace Ojs\AdminBundle\Controller;

use Ojs\AdminBundle\Entity\SystemSetting;
use Ojs\AdminBundle\Form\Type\SystemSettingsType;
use Ojs\CoreBundle\Controller\OjsController;
use Symfony\Component\HttpFoundation\Request;

class AdminSystemSettingsController extends OjsController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $systemSetting = $em->getRepository('OjsAdminBundle:SystemSetting')->findOneBy([]);
        if(!$systemSetting){
            $systemSetting = $this->createSystemSetting();
        }
        $form = $this->createForm(new SystemSettingsType(), $systemSetting);
        $form->handleRequest($request);

        if($form->isValid() && $request->getMethod() == 'POST'){
            $em->persist($systemSetting);
            $em->flush();

            $this->successFlashBag('successful.update');
        }

        return $this->render(
            'OjsAdminBundle:AdminSystemSetting:index.html.twig',
            array(
                'entity' => $systemSetting,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @return SystemSetting
     */
    private function createSystemSetting()
    {
        $em = $this->getDoctrine()->getManager();
        $systemSetting = new SystemSetting();
        $systemSetting
            ->setArticleSubmissionActive(true)
            ->setJournalApplicationActive(true)
            ->setPublisherApplicationActive(true)
            ->setUserRegistrationActive(true)
            ->setSystemFooterScript('')
        ;
        $em->persist($systemSetting);
        $em->flush();

        return $systemSetting;
    }
}
