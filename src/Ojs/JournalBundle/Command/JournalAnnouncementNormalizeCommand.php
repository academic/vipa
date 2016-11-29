<?php

namespace Ojs\JournalBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Ojs\JournalBundle\Entity\JournalAnnouncement;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Ojs\JournalBundle\Entity\JournalAnnouncementTranslation;

/**
 * @deprecated this normalizer for BC concerns. we can remove after a while
 * Class JournalAnnouncementNormalizeCommand
 * @package Ojs\JournalBundle\Command
 */
class JournalAnnouncementNormalizeCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('ojs:journal:announcement:normalize')
            ->setDescription('Journal Announcement Normalize')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io               = new SymfonyStyle($input, $output);
        $this->container        = $this->getContainer();
        $this->em               = $this->container->get('doctrine')->getManager();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io->title($this->getDescription());
        $this->io->progressStart(count($this->getAnnouncements()));
        $counter = 1;

        foreach($this->getAnnouncements() as $announcement){
            if(!$this->haveTranslation($announcement['id'])){
                $this->addTranslation($announcement);
                $this->io->progressAdvance(1);
                $counter = $counter+1;
                if($counter%50 == 0){
                    $this->em->flush();
                }
            }
        }
        $this->em->flush();
        $this->io->success('All process finished');
    }

    private function getAnnouncements()
    {
        $sql = <<<SQL
          SELECT journal_announcement.id,journal_announcement.title,journal_announcement.content FROM journal_announcement
SQL;
        
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id','id');
        $rsm->addScalarResult('title','title');
        $rsm->addScalarResult('content','content');
        $query = $this->em->createNativeQuery($sql, $rsm);

        return $query->getResult();
    }

    private function haveTranslation($id)
    {
        $sql = <<<SQL
          SELECT journal_announcement_translations.id FROM journal_announcement_translations WHERE translatable_id = ?
SQL;
        
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id','id');
        $query = $this->em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $id);

        return $query->getResult();
    }


    private function addTranslation($announcement)
    {
        $entity  = $this->em->getRepository('OjsJournalBundle:JournalAnnouncement')->find($announcement['id']);

        if(!$entity){
            return;
        }
        $entity->setContent($announcement['content']);
        $entity->setTitle($announcement['title']);

        $this->em->persist($entity);
    }
}
