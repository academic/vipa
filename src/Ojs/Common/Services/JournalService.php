<?php

namespace Ojs\Common\Services;

use Doctrine\ODM\MongoDB\DocumentManager;
use \Doctrine\ORM\EntityManager;
use Ojs\AnalyticsBundle\Document\ObjectViews;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Common methods for journal
 */
class JournalService
{

    private $em;
    /* @var \Symfony\Component\DependencyInjection\Container */
    private $container;
    private $session;

    private $dm;

    /**
     *
     * @param ContainerInterface $container
     * @param EntityManager $em
     */
    public function __construct(ContainerInterface $container, EntityManager $em, DocumentManager $dm)
    {
        $this->container = $container;
        $this->session = $this->container->get('session');
        $this->em = $em;
        $this->dm = $dm;
    }

    /**
     * get user's current selected journal
     * @return \Ojs\JournalBundle\Entity\Journal
     * @throws HttpException
     */
    public function getSelectedJournal($redirect = true)
    {
        $em = $this->container->get('doctrine')->getManager();
        $selectedJournalId = $this->session->get("selectedJournalId");
        $selectedJournal = $selectedJournalId ? $em->getRepository('OjsJournalBundle:Journal')->find($selectedJournalId) : null;
        if ($selectedJournal) {
            return $selectedJournal;
        }
        if ($redirect) {
            // this seems messy
            try {
                header("Location: " . $this->container->get('router')->generate('user_join_journal'), TRUE, 302);
            } catch (Exception $e) {

            }
            exit;
        }
        return false;
    }

    /**
     * get default institution record
     * @return \Ojs\JournalBundle\Entity\Institution
     */
    public function getDefaultInstitution($redirect = true)
    {
        $em = $this->container->get('doctrine')->getManager();
        $slug = $this->container->getParameter('defaultInstitutionSlug');
        $intitution = $slug ? $em->getRepository('OjsJournalBundle:Institution')
            ->findOneBy(array('slug' => $slug)) : null;
        return $intitution;
    }

    /**
     *
     * @param \Ojs\JournalBundle\Entity\Journal $journal
     * @return boolean
     */
    public function generateUrl($journal)
    {
        $institution = $journal->getInstitution();
        $institutionSlug = $institution ? $institution->getSlug() : $this->container->getParameter('defaultInstitutionSlug');
        return $this->container->get('router')
            ->generate('ojs_journal_index', array('slug' => $journal->getSlug(), 'institution' => $institutionSlug), Router::ABSOLUTE_URL);
    }

    public function setSelectedJournal($journalId)
    {
        $this->session->set('selectedJournalId', $journalId);
        return $journalId;
    }

    /**
     *
     * @param integer $journalId
     * @param integer $page
     * @param integer $limit
     * @return mixed user list
     */
    public function getUsers($journalId, $page, $limit)
    {
        $users = $this->em->getRepository('OjsUserBundle:UserJournalRole')
            ->createQueryBuilder('j')
            ->where('j.journalId = :id')
            ->setParameter('id', $journalId)
            //->orderBy('id', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult($page)
            ->getQuery()
            ->getResult();
        return $users;
    }

    /**
     * @param $journal
     * @return array
     */
    public function journalStats(Journal $journal)
    {
        $object_view = $this->dm->getRepository('OjsAnalyticsBundle:ObjectViews');
        $journal_stats = $object_view->findBy(['entity' => 'journal', 'objectId' => $journal->getId()]);
        $groupped_journal_stats = [];
        $counted_article_stats=[];
        foreach ($journal_stats as $js) {
            /** @var ObjectViews $js */
            $dateKey = $js->getLogDate()->format('d-M-Y');
            $groupped_journal_stats[$dateKey] = isset($groupped_journal_stats[$dateKey]) ? $groupped_journal_stats[$dateKey] + 1 : 1;
            foreach ($journal->getArticles() as $article) {
                $article_stats = $object_view->findBy(['entity'=>'article','objectId'=>$article->getId()]);
                foreach ($article_stats as $article_stat) {
                    if($article_stat->getLogDate()->format('d-M-Y')==$dateKey && !in_array($article_stat->getId(),$counted_article_stats)){
                        $counted_article_stats[]=$article_stat->getId();
                        $groupped_journal_stats[$dateKey]=isset($groupped_journal_stats[$dateKey]) ? $groupped_journal_stats[$dateKey] + 1 : 1;
                    }
                }

            }

        }
        ksort($groupped_journal_stats);
        return $groupped_journal_stats;
    }

    public function journalsArticlesStats(Journal $journal)
    {
        $object_view = $this->dm->getRepository('OjsAnalyticsBundle:ObjectViews');
        $stats = [];
        $affetted_articles=[];
        foreach ($journal->getArticles() as $article) {
            $articleStats = $object_view->findBy(['entity' => 'article', 'objectId' => $article->getId()]);
            if(!$articleStats)
                continue;
            foreach ($articleStats as $stat) {
                $dateKey = $stat->getLogDate()->format("d-M-Y");
                $stats[$dateKey][$article->getId()] = [
                    'hit'=>isset($stats[$dateKey][$article->getId()]['hit']) ? $stats[$dateKey][$article->getId()]['hit'] + 1 : 1,
                    'title'=>$article->getTitle()
                ];
            }
            $affetted_articles[]=['id'=>$article->getId(),'title'=>$article->getTitle()];
        }
        foreach ($stats as $date=>$stat) {
            foreach ($affetted_articles as $article) {
                if(!isset($stats[$date][$article['id']])){
                    $stats[$date][$article['id']]=[
                        'hit'=>0,
                        'title'=>$article['title']
                    ];
                }
            }

        }
        ksort($stats);
        return [
            'stats'=>$stats,
            'articles'=>$affetted_articles
        ];
    }
}
