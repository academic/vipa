<?php

namespace Ojs\WorkflowBundle\Controller;

use \Symfony\Component\HttpFoundation\Request;

class WorkflowTemplateController extends \Ojs\Common\Controller\OjsController
{

    /**
     * @return Response
     */
    public function indexAction()
    {
        $templates = $this->get('doctrine_mongodb')->getRepository('OjsWorkflowBundle:JournalWorkflowTemplate')->findAll();
        return $this->render('OjsWorkflowBundle:WorkflowStep:templates.html.twig', array('templates' => $templates));
    }

    /**
     * 
     * @param string $id template document id
     * @return Response
     */
    public function showAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $template = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowTemplate')->find($id);

        $steps = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowTemplateStep')->createQueryBuilder()
                ->field('template.$id')
                ->equals(new \MongoId($template->getId()))
                ->getQuery()
                ->execute(); 
        return $this->render('OjsWorkflowBundle:WorkflowStep:template.html.twig', array('template' => $template, 'steps' => $steps));
    }

}
