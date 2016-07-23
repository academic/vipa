<?php

namespace Ojs\SiteBundle\Controller;

use Ojs\CoreBundle\Controller\OjsController as Controller;
use Doctrine\ORM\EntityManager;
use Ojs\JournalBundle\Entity\BlockRepository;
use Ojs\JournalBundle\Entity\Journal;
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
        $blockRepo = $em->getRepository('OjsJournalBundle:Block');
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);
        $data['announcements'] = $em->getRepository('OjsJournalBundle:JournalAnnouncement')->findBy(
            ['journal' => $journal],
            ['id' => 'DESC']
        );

        $data['page'] = 'announcement';
        $data['blocks'] = $blockRepo->journalBlocks($journal);
        $data['journal'] = $journal;

        return $this->render('OjsSiteBundle::Journal/announcement_index.html.twig', $data);
    }

    public function journalPageDetailAction($slug, $journal_slug)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $journal_slug]);
        $this->throw404IfNotFound($journal);
        $page = $em->getRepository('OjsJournalBundle:JournalPage')->findOneBy(['journal' => $journal, 'slug' => $slug]);
        $this->throw404IfNotFound($page);

        return $this->render('OjsSiteBundle:JournalCms:page.html.twig', ['journalPage' => $page]);
    }

    public function journalPostDetailAction($slug, $journal_slug)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $journal_slug]);
        $this->throw404IfNotFound($journal);

        $post = $em->getRepository('OjsJournalBundle:JournalPost')->findOneBy(['journal' => $journal, 'slug' => $slug]);
        $this->throw404IfNotFound($post);


        return $this->render('OjsSiteBundle:JournalCms:post.html.twig', ['post' => $post]);
    }

}
