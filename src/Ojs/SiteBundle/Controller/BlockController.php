<?php
/**
 * Date: 28.11.14
 * Time: 10:26
 */

namespace Ojs\SiteBundle\Controller;


use Ojs\SiteBundle\Entity\Block;
use Ojs\SiteBundle\Entity\BlockLink;
use Ojs\SiteBundle\Form\BlockLinkType;
use Ojs\SiteBundle\Form\BlockType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class BlockController extends Controller
{
    public function createAction(Request $request, $object, $type, $id = 0)
    {
        $data = [];
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $Block = $id ? $em->find('OjsSiteBundle:Block', $id) : new Block();
        $form = $this->createForm(new BlockType(), $Block, ['object_id' => $object, 'object_type' => $type]);
        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $em->persist($Block);
                $em->flush();
                return $this->redirect($this->get('router')->generate('ojs_journal_index', ['journal_id' => $object]));
            }
            //@todo return value only for journal.
            $this->get('session')->getFlashBag()->add('error', 'All field is required');
        }
        $data['form'] = $form->createView();
        return $this->render("OjsSiteBundle:Block:create.html.twig", $data);
    }

    public function newLinkAction(Request $request, Block $block)
    {
        $data = [];
        $BlockLink = new BlockLink();
        $form = $this->createForm(new BlockLinkType(), $BlockLink, ['block_id' => $block->getId()]);
        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $BlockLink->setBlock($block);
                /** @var \Doctrine\ORM\EntityManager $em */
                $em = $this->getDoctrine()->getManager();
                $em->persist($BlockLink);
                $block->addLink($BlockLink);
                $em->persist($block);
                $em->flush();
                return $this->redirect($this->get('router')->generate('ojs_journal_index', ['journal_id' => $block->getObjectId()]));
            }
            $this->get('session')->getFlashBag()->add('error', 'All fields are required');
        }
        $data['form'] = $form->createView();
        return $this->render('OjsSiteBundle:BlockLink:create.html.twig', $data);
    }

    public function deleteAction(Request $request, $object, $type, Block $block)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->remove($block);
        $em->flush();
        return $this->redirect($this->get('router')->generate('ojs_journal_index', ['journal_id' => $block->getObjectId()]));
    }

    public function orderAction(Request $request, Block $block, $order)
    {

    }

    public function orderLinkAction(Request $request, BlockLink $block_link, $order)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $block_link->setLinkOrder((int)$order);
        $em->persist($block_link);
        $em->flush();
        return JsonResponse::create(['status' => true]);
    }
}