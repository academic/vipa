<?php

namespace Ojs\SiteBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use Ojs\SiteBundle\Entity\Block;
use Ojs\SiteBundle\Entity\BlockLink;
use Ojs\SiteBundle\Form\Type\BlockLinkType;
use Ojs\SiteBundle\Form\Type\BlockType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class BlockController extends Controller
{
    /**
     * @param Request $request
     * @param $object
     * @param $type
     * @param int $id
     * @return RedirectResponse|Response
     * @throws AccessDeniedException
     */
    public function createAction(Request $request, $object, $type, $id = 0)
    {
        $em = $this->getDoctrine()->getManager();
        switch ($type) {
            case 'journal':
                $object = $em->find('OjsJournalBundle:Journal', $object);
                break;
            default:
                throw new NotFoundHttpException();
        }
        if (!$this->isGranted('CREATE', $object, 'block')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $data = [];
        $Block = $id ? $em->find('OjsSiteBundle:Block', $id) : new Block();
        $form = $this->createForm(new BlockType(), $Block, ['object_id' => $object->getId(), 'object_type' => $type]);
        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $em->persist($Block);
                $em->flush();

                return $this->redirect($this->get('ojs.journal_service')->generateUrl($object));
            }
            //@todo return value only for journal.
            $this->get('session')->getFlashBag()->add('error', 'All field is required');
        }
        $data['form'] = $form->createView();

        return $this->render("OjsSiteBundle:Block:create.html.twig", $data);
    }

    /**
     * @param  Request $request
     * @param  Block $block
     * @param  int $id
     * @return RedirectResponse|Response
     */
    public function newLinkAction(Request $request, Block $block, $id = 0)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();
        $BlockLink = $id ? $em->find('OjsSiteBundle:BlockLink', $id) : new BlockLink();
        $form = $this->createForm(new BlockLinkType(), $BlockLink, ['block_id' => $block->getId()]);
        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $BlockLink->setBlock($block);

                $em->persist($BlockLink);
                $block->addLink($BlockLink);
                $em->persist($block);
                $em->flush();
                $journal = $em->getRepository('OjsJournalBundle:Journal')->find($block->getObjectId());

                return $this->redirect($this->get('ojs.journal_service')->generateUrl($journal));
            }
            $this->get('session')->getFlashBag()->add('error', 'All fields are required');
        }
        $data['form'] = $form->createView();

        return $this->render('OjsSiteBundle:BlockLink:create.html.twig', $data);
    }

    /**
     * @param $object
     * @param $type
     * @param  Block $block
     * @return RedirectResponse
     */
    public function deleteAction($object, $type, Block $block)
    {

        $em = $this->getDoctrine()->getManager();
        /**
         * only journals has blocks for now
         */
        $journal = $em->find('OjsJournalBundle:Journal', $object);
        if (!$this->isGranted('DELETE', $journal, 'block')) {
            throw new AccessDeniedException("You are not authorized for view this page!");
        }
        $em->remove($block);
        $em->flush();

        return $this->redirect($this->get('ojs.journal_service')->generateUrl($journal));
    }

    /**
     * @param $object
     * @param $type
     * @param  BlockLink $block_link
     * @return RedirectResponse
     */
    public function deleteLinkAction($object, $type, BlockLink $block_link)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($block_link);
        $em->flush();

        return $this->redirect($this->get('ojs.journal_service')->generateUrl($object));
    }

    /**
     * @param  Block $block
     * @param $order
     * @return Response
     */
    public function orderAction(Block $block, $order)
    {
        $em = $this->getDoctrine()->getManager();

        $block->setBlockOrder($order);
        $em->persist($block);
        $em->flush();

        return JsonResponse::create(['status' => true]);
    }

    /**
     * @param  BlockLink $block_link
     * @param $order
     * @return JsonResponse
     */
    public function orderLinkAction(BlockLink $block_link, $order)
    {
        $em = $this->getDoctrine()->getManager();

        $block_link->setLinkOrder((int)$order);
        $em->persist($block_link);
        $em->flush();

        return JsonResponse::create(['status' => true]);
    }
}
