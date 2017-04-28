<?php

namespace Vipa\SiteBundle\Controller;

use Vipa\CoreBundle\Controller\VipaController as Controller;
use Doctrine\ORM\EntityManager;
use Vipa\CoreBundle\Params\JournalStatuses;
use Vipa\CoreBundle\Params\PublisherStatuses;
use Vipa\JournalBundle\Entity\BlockRepository;
use Vipa\JournalBundle\Entity\Journal;
use Symfony\Component\HttpFoundation\Response;

class JournalCmsController extends Controller
{
    /**
     * @param $slug
     * @return Response
     */
    public function announcementIndexAction($slug)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var BlockRepository $blockRepo */
        $blockRepo = $em->getRepository('VipaJournalBundle:Block');
        /** @var Journal $journal */
        $journal = $em->getRepository('VipaJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);

        if($journal->getStatus() !== JournalStatuses::STATUS_PUBLISHED || $journal->getPublisher()->getStatus() !== PublisherStatuses::STATUS_COMPLETE ){
            $journal = null;
            $this->throw404IfNotFound($journal);
        }

        $data['announcements'] = $em->getRepository('VipaJournalBundle:JournalAnnouncement')->findBy(
            ['journal' => $journal],
            ['id' => 'DESC']
        );

        $data['page'] = 'announcement';
        $data['blocks'] = $blockRepo->journalBlocks($journal);
        $data['journal'] = $journal;

        return $this->render('VipaSiteBundle::Journal/announcement_index.html.twig', $data);
    }

    public function journalPageDetailAction($slug, $journal_slug,$isJournalHosting=false)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $em->getRepository('VipaJournalBundle:Journal')->findOneBy(['slug' => $journal_slug]);
        $this->throw404IfNotFound($journal);

        if($journal->getStatus() !== JournalStatuses::STATUS_PUBLISHED || $journal->getPublisher()->getStatus() !== PublisherStatuses::STATUS_COMPLETE ){
            $journal = null;
            $this->throw404IfNotFound($journal);
        }

        $page = $em->getRepository('VipaJournalBundle:JournalPage')->findOneBy(['journal' => $journal, 'slug' => $slug]);
        $this->throw404IfNotFound($page);

        return $this->render('VipaSiteBundle:JournalCms:page.html.twig', ['journalPage' => $page,'isJournalHosting' => $isJournalHosting]);
    }

    public function journalPostDetailAction($slug, $journal_slug)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $em->getRepository('VipaJournalBundle:Journal')->findOneBy(['slug' => $journal_slug]);
        $this->throw404IfNotFound($journal);

        if($journal->getStatus() !== JournalStatuses::STATUS_PUBLISHED || $journal->getPublisher()->getStatus() !== PublisherStatuses::STATUS_COMPLETE ){
            $journal = null;
            $this->throw404IfNotFound($journal);
        }


        $post = $em->getRepository('VipaJournalBundle:JournalPost')->findOneBy(['journal' => $journal, 'slug' => $slug]);
        $this->throw404IfNotFound($post);

        return $this->render('VipaSiteBundle:JournalCms:post.html.twig', ['post' => $post]);
    }

}
