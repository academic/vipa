<?php

namespace Ojs\JournalBundle\Controller\JournalSetup;

use Doctrine\ORM\EntityManager;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\SiteBundle\Entity\Block;
use Ojs\SiteBundle\Entity\BlockLink;
use Symfony\Component\HttpFoundation\Request;
use Ojs\JournalBundle\Form\JournalSetup\Step6;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Journal Setup Wizard Step controller.
 *
 */
class JournalSetupStep6Controller extends Controller
{
    /**
     * Journal Setup Wizard Step 6 - Saves Journal 's step 6 data
     * @param Request $request
     * @param $setupId
     * @return JsonResponse
     */
    public function updateAction(Request $request, $setupId)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $em = $this->getDoctrine()->getManager();
        $setup = $dm->getRepository('OjsJournalBundle:JournalSetupProgress')->findOneById($setupId);
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($setup->getJournalId());
        $step6Form = $this->createForm(new Step6(), $journal);
        $step6Form->handleRequest($request);
        if ($step6Form->isValid()) {
            $journal->setSetupStatus(true);
            $em->flush();
            //remove journal setup progress data
            $dm->remove($setup);
            $dm->flush();
            $journalLink = $this->get('ojs.journal_service')->generateUrl($journal);
            return new JsonResponse(array(
                'success' => '1',
                'journalLink' => $journalLink
            ));
        } else {
            return new JsonResponse(array(
                'success' => '0'));
        }
    }

    /**
     * manager current journal setup step 6
     * @param Request $request
     * @return JsonResponse
     */
    public function managerUpdateAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $currentJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        $step6Form = $this->createForm(new Step6(), $currentJournal);
        $step6Form->handleRequest($request);
        $router = $this->get('router');
        if ($step6Form->isValid()) {
            //add blocks
            $twig = $this->get('okulbilisimcmsbundle.twig.post_extension');
            $journalKey = $twig->encode($currentJournal);
            $pages = $em->getRepository("OkulbilisimCmsBundle:Post")->findBy(['object'=>$journalKey,'objectId'=>$currentJournal->getId()]);
            if($pages){
                $blockRepo = $em->getRepository("OjsSiteBundle:Block");
                $checkPagesBlock = $blockRepo->findOneBy([
                    'type'=>'link',
                    'object_type'=>'journal',
                    'object_id'=>$currentJournal->getId(),
                    'title'=>'Pages'
                ]);
                if(!$checkPagesBlock)
                {
                    $block = new Block();
                    $block->setType('link')
                        ->setObjectType('journal')
                        ->setObjectId($currentJournal->getId())
                        ->setColor('primary')
                        ->setTitle("Pages")
                    ;
                    $em->persist($block);
                    $i = 1;
                    foreach ($pages as $page) {
                        $blockLink = new BlockLink();
                        $blockLink
                            ->setBlock($block)
                            ->setPost($page)
                            ->setText($page->getTitle())
                            ->setUrl($router->generate('ojs_journal_index_page_detail',[
                                'journal_slug'=>$currentJournal->getSlug(),
                                'slug'=>$page->getSlug(),
                                'institution'=>$currentJournal->getInstitution()->getSlug()
                            ]))
                            ->setLinkOrder($i)
                        ;
                        $i++;
                        $em->persist($blockLink);
                    }
                }
            }
            $currentJournal->setSetupStatus(true);
            $em->flush();
            $journalLink = $this->get('ojs.journal_service')->generateUrl($currentJournal);
            return new JsonResponse([
                'success' => '1',
                'journalLink' => $journalLink
            ]
            );
        } else {
            return new JsonResponse(array(
                'success' => '0'));
        }
    }
}
