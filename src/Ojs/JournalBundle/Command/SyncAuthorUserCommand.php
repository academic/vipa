<?php

namespace Ojs\JournalBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Ojs\JournalBundle\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SyncAuthorUserCommand
 * @package Ojs\JournalBundle\Command
 */
class SyncAuthorUserCommand extends ContainerAwareCommand
{
    const STEP = 50000;

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
            ->setName('ojs:sync:author:user')
            ->setDescription('Guess author user and synchronize')
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
        $totalAuthorCount = $this->getAuthorCount();
        $this->io->progressStart($totalAuthorCount);
        $rsm = new ResultSetMapping();

        for ($count = 0; $count <= $totalAuthorCount; $count+=self::STEP){
            $sql = <<<SQL
        UPDATE author
        SET email = users.email
        FROM users
        WHERE author.user_id = users.id
        AND author.id > ?
        and author.id < ?
        and author.user_id is not null
SQL;
            $query = $this->em->createNativeQuery($sql, $rsm);
            $query->setParameter(1, $count);
            $query->setParameter(2, $count+self::STEP);
            $query->getResult();

            if(self::STEP> $totalAuthorCount){
                $this->io->progressFinish();
            }else{
                $this->io->progressAdvance(self::STEP);
            }
        }
        $this->io->newLine(2);
        $this->io->success('All process finished');
    }

    private function getAuthorCount()
    {
        return $this->em->getRepository('OjsJournalBundle:Author')
            ->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
