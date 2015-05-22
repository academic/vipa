<?php

namespace Ojs\WorkflowBundle\Controller;

use Ojs\Common\Controller\OjsController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Ojs\WorkflowBundle\Document\ReviewFormItem;
use Symfony\Component\Yaml;
class ReviewFormItemController extends OjsController
{

    /**
     *
     * @param  string           $formId
     * @return RedirectResponse
     */
    public function loadDefaultItemsAction($formId)
    {
        $yamlParser = new Yaml\Parser();
        $formTemplates = $yamlParser->parse(file_get_contents(
                        $this->container->getParameter('kernel.root_dir').
                        '/../src/Ojs/WorkflowBundle/Resources/data/reviewformtemplates.yml'
        ));
        $standartTemplate = $formTemplates['standart_template'];
        $dm = $this->get('doctrine_mongodb')->getManager();

        // danger remove old questions
        $qb = $dm->createQueryBuilder('OjsWorkflowBundle:ReviewFormItem');
        $qb->remove()
                ->field('formId')->equals(new \MongoId($formId))
                ->getQuery()
                ->execute();

        foreach ($standartTemplate as $item) {
            $formItem = new ReviewFormItem();
            $formItem->setFields($item['fields']);
            $formItem->setTitle($item['title']);
            $formItem->setFieldset($item['fieldset']);
            $formItem->setFormId($formId);
            $formItem->setInputType($item['inputtype']);
            $formItem->setMandatory($item['mandatory']);
            $dm->persist($formItem);
            $dm->flush();
        }

        return $this->redirect($this->generateUrl('ojs_review_form_items', array('formId' => $formId)));
    }

    /**
     * list review forms items
     * @param  string                                     $formId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($formId)
    {
        $formItems = $this->get('doctrine_mongodb')
                ->getRepository('OjsWorkflowBundle:ReviewFormItem')
                ->findBy(array('formId' => new \MongoId($formId)));

        $form = $this->get('doctrine_mongodb')
                ->getRepository('OjsWorkflowBundle:ReviewForm')
                ->find($formId);
        $this->throw404IfNotFound($form);

        return $this->render('OjsWorkflowBundle:ReviewFormItem:index.html.twig', array(
                    'formItems' => $formItems,
                    'form' => $form,
        ));
    }

    /**
     * render "new review form" form
     * @param  string   $formId
     * @return Response
     */
    public function newAction($formId)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $form = $dm->getRepository('OjsWorkflowBundle:ReviewForm')->find($formId);

        return $this->render('OjsWorkflowBundle:ReviewFormItem:new.html.twig', array('form' => $form,
        ));
    }

    /**
     * insert new review form
     * @param  Request  $request
     * @param  string   $formId
     * @return Response
     */
    public function createAction(Request $request, $formId)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $form = $dm->getRepository('OjsWorkflowBundle:ReviewForm')->find($formId);

        $formItem = new ReviewFormItem();
        $formItem->setTitle($request->get('title'));
        $formItem->setFieldset($request->get('fieldset'));
        $formItem->setMandatory($request->get('mandatory'));
        $formItem->setConfidential($request->get('confidential'));
        $formItem->setFormId($formId);
        $formItem->setInputType($request->get('inputtype'));
        // explode fields by new line and filter null values
        $fields = array_filter(explode("\n", $request->get('fields')));
        $formItem->setFields($fields);
        $dm->persist($formItem);
        $dm->flush();
        $this->successFlashBag('successful.create');

        return $this->redirectToRoute('ojs_review_form_items_show', [
            'id' => $formItem->getId(),
            'form' => $form,
            ]
        );
    }

    /**
     *
     * @param  string   $id
     * @return Response
     */
    public function editAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        /** @var ReviewFormItem $formItem */
        $formItem = $dm->getRepository('OjsWorkflowBundle:ReviewFormItem')->find($id);
        $form = $dm->getRepository('OjsWorkflowBundle:ReviewForm')->find($formItem->getFormId());

        return $this->render('OjsWorkflowBundle:ReviewFormItem:edit.html.twig', array(
                    'formItem' => $formItem,
                    'form' => $form, )
        );
    }

    /**
     *
     * @param  string           $id
     * @return RedirectResponse
     */
    public function deleteAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $formItem = $dm->getRepository('OjsWorkflowBundle:ReviewFormItem')->find($id);
        $dm->remove($formItem);
        $dm->flush();
        $this->successFlashBag('successful.remove');

        return $this->redirectToRoute('ojs_review_form_items', array('formId' => $formItem->getFormId()));
    }

    /**
     *
     * @param  string   $id
     * @return Response
     */
    public function showAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $formItem = $dm->getRepository('OjsWorkflowBundle:ReviewFormItem')->find($id);
        $form = $dm->getRepository('OjsWorkflowBundle:ReviewForm')->find($formItem->getFormId());

        return $this->render('OjsWorkflowBundle:ReviewFormItem:show.html.twig', array(
                    'formItem' => $formItem,
                    'form' => $form,
                        )
        );
    }

    /**
     *
     * @param  Request          $request
     * @param  string           $id
     * @return RedirectResponse
     */
    public function updateAction(Request $request, $id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $repo = $dm->getRepository('OjsWorkflowBundle:ReviewFormItem');
        $formItem = $repo->find($id);

        $formItem->setTitle($request->get('title'));
        $formItem->setFieldset($request->get('fieldset'));
        $formItem->setMandatory($request->get('mandatory'));
        $formItem->setConfidential($request->get('confidential'));
        $formItem->setInputType($request->get('inputtype'));
        // explode fields by new line and filter null values
        $fields = array_filter(explode("\n", $request->get('fields')));
        $formItem->setFields($fields);
        $dm->persist($formItem);
        $dm->flush();

        $dm->persist($formItem);
        $dm->flush();
        $this->successFlashBag('successful.update');

        return $this->redirect($this->generateUrl('ojs_review_form_items_show', array('id' => $id)));
    }
}
