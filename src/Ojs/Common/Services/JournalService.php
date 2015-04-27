<?php

namespace Ojs\Common\Services;

use \Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Common methods for journal
 */
class JournalService {

    private $em;
    /* @var \Symfony\Component\DependencyInjection\Container  */
    private $container;
    private $session;

    /**
     * 
     * @param ContainerInterface $container
     * @param EntityManager $em
     */
    public function __construct(ContainerInterface $container, EntityManager $em)
    {
        $this->container = $container;
        $this->session = $this->container->get('session');
        $this->em = $em;
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
     *  @param integer $page
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

}
