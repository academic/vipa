<?php

namespace Ojs\JournalBundle\Controller\JournalSetup;

use Doctrine\ORM\EntityManager;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\JournalSection;
use Ojs\JournalBundle\Form\Type\JournalSetup\Step6;
use Ojs\SiteBundle\Entity\Block;
use Ojs\SiteBundle\Entity\BlockLink;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Journal Setup Wizard Step controller.
 *
 */
class JournalSetupStep6Controller extends Controller
{
    /**
     * TODO : function must be inspected before remove. Now we are not using this method.
     * manager current journal setup step 6
     * @param  Request      $request
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
            $twig = $this->get('ojs.cms.twig.post_extension');
            $journalKey = $twig->encode($currentJournal);
            $pages = $em->getRepository("OkulbilisimCmsBundle:Post")->findBy(
                ['object' => $journalKey, 'objectId' => $currentJournal->getId()]
            );
            if ($pages) {
                $blockRepo = $em->getRepository("OjsSiteBundle:Block");
                $checkPagesBlock = $blockRepo->findOneBy(
                    [
                        'type' => 'link',
                        'objectType' => 'journal',
                        'objectId' => $currentJournal->getId(),
                        'title' => 'Pages',
                    ]
                );
                if (!$checkPagesBlock) {
                    $block = new Block();
                    $block->setType('link')
                        ->setObjectType('journal')
                        ->setObjectId($currentJournal->getId())
                        ->setColor('primary')
                        ->setTitle("Pages");
                    $em->persist($block);
                    $i = 1;
                    foreach ($pages as $page) {
                        $blockLink = new BlockLink();
                        $blockLink
                            ->setBlock($block)
                            ->setPost($page)
                            ->setText($page->getTitle())
                            ->setUrl(
                                $router->generate(
                                    'ojs_site_journal_page',
                                    [
                                        'journal_slug' => $currentJournal->getSlug(),
                                        'slug' => $page->getSlug(),
                                        'institution' => $currentJournal->getInstitution()->getSlug(),
                                    ]
                                )
                            )
                            ->setLinkOrder($i);
                        $i++;
                        $em->persist($blockLink);
                    }
                }
            }
            $journalSections = $currentJournal->getSections();
            if (count($journalSections) == 0) {
                $journalSection = new JournalSection();
                $journalSection->setTitle($this->get('translator')->trans("Articles"))
                    ->setAllowIndex(true)
                    ->setJournal($currentJournal)
                    ->setJournalId($currentJournal->getId());
                $em->persist($journalSection);
                $currentJournal->addSection($journalSection);
            }
            $currentJournal->setSetupStatus(true);
            $em->flush();
            $journalLink = $this->get('ojs.journal_service')->generateUrl($currentJournal);

            return new JsonResponse(
                [
                    'success' => '1',
                    'journalLink' => $journalLink,
                ]
            );
        } else {
            return new JsonResponse(
                array(
                    'success' => '0',
                )
            );
        }
    }
}
