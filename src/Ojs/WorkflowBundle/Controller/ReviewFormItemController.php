<?php

namespace Ojs\WorkflowBundle\Controller;

use \Symfony\Component\HttpFoundation\Request;

class ReviewFormItemController extends \Ojs\Common\Controller\OjsController
{

    /**
     * list review forms items
     * @param string $formId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($formId)
    {
        $formItems = $this->get('doctrine_mongodb')
                ->getRepository('OjsWorkflowBundle:ReviewFormItem')
                ->findBy(array('formId' =>  new \MongoId($formId)));

        $form = $this->get('doctrine_mongodb')
                ->getRepository('OjsWorkflowBundle:ReviewForm')
                ->find($formId);
        $this->throw404IfNotFound($form);
        return $this->render('OjsWorkflowBundle:ReviewFormItem:index.html.twig', array(
                    'formItems' => $formItems,
                    'form' => $form
        ));
    }

    /**
     * render "new review form" form
     * @param string $formId 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction($formId)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $form = $dm->getRepository('OjsWorkflowBundle:ReviewForm')->find($formId);
        return $this->render('OjsWorkflowBundle:ReviewFormItem:new.html.twig', array('form' => $form
        ));
    }

    /**
     * insert new review form 
     * @param Request $request
     * @param string $formId 
     * @return Response
     */
    public function createAction(Request $request, $formId)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $form = $dm->getRepository('OjsWorkflowBundle:ReviewForm')->find($formId);

        $formItem = new \Ojs\WorkflowBundle\Document\ReviewFormItem();
        $formItem->setTitle($request->get('title'));
        $formItem->setMandotary($request->get('mandotary'));
        $formItem->setConfidential($request->get('confidential'));
        $formItem->setFormId($formId);
        $formItem->setInputType($request->get('inputtype'));
        // explode fields by new line and filter null values 
        $fields = array_filter(explode("\n", $request->get('fields')));
        $formItem->setFields($fields);
        $dm->persist($formItem);
        $dm->flush();

        return $this->redirect(
                        $this->generateUrl('ojs_review_form_items_show', array(
                            'id' => $formItem->getId(),
                            'form' => $form
                        ))
        );
    }

    /**
     * 
     * @param string $id
     * @return Response
     */
    public function editAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $formItem = $dm->getRepository('OjsWorkflowBundle:ReviewFormItem')->find($id);
        $form = $dm->getRepository('OjsWorkflowBundle:ReviewForm')->find($formItem->getFormId());

        return $this->render('OjsWorkflowBundle:WorkflowStep:edit.html.twig', array(
                    'formItem' => $formItem,
                    'form' => $form)
        );
    }

    /**
     * 
     * @param string $id
     * @return ResponseRedirect
     */
    public function deleteAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $formItem = $dm->getRepository('OjsWorkflowBundle:ReviewFormItem')->find($id);
        $dm->remove($formItem);
        $dm->flush();
        return $this->redirect($this->generateUrl('ojs_review_form_items')
        );
    }

    /**
     * 
     * @param string $id 
     * @return Response
     */
    public function showAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $formItem = $dm->getRepository('OjsWorkflowBundle:ReviewFormItem')->find($id);
        $form = $dm->getRepository('OjsWorkflowBundle:ReviewForm')->find($formItem->getFormId());

        return $this->render('OjsWorkflowBundle:ReviewFormItem:show.html.twig', array(
                    'formItem' => $formItem,
                    'form' => $form
                        )
        );
    }

    /**
     * 
     * @param string $id
     * @return ResponseRedirect
     */
    public function updateAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $repo = $dm->getRepository('OjsWorkflowBundle:ReviewFormItem');
        $formItem = $repo->find($id);
        $dm->persist($formItem);
        $dm->flush();
        return $this->redirect($this->generateUrl('workflowsteps_show', array('id' => $id)));
    }

}
