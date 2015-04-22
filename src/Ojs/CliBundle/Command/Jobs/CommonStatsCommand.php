<?php

namespace Ojs\CliBundle\Command\Jobs;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use \Ojs\JournalBundle\Entity\Sums;

class CommonStatsCommand extends ContainerAwareCommand {

    protected function configure()
    {
        $this
                ->setName('ojs:count:common')
                ->setDescription('Count save results for common entities like  Journal, Article, Institution, Subject and User');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>ojs:count:common started</info>\n");

        $doctrine = $this->getContainer()->get('doctrine');
        $output->writeln("<info>Counting  journals</info>\n");

        $em = $doctrine->getManager();
        $this->countSaveEntity($em, 'OjsJournalBundle:Journal', $output);
        $this->countSaveEntity($em, 'OjsJournalBundle:Article', $output);
        $this->countSaveEntity($em, 'OjsJournalBundle:Subject', $output);
        $this->countSaveEntity($em, 'OjsJournalBundle:Institution', $output);
        $this->countSaveEntity($em, 'OjsUserBundle:User', $output);
    }

    /**
     * @param Doctrine\ORM\EntityManager $em
     * @param string $entityName
     * @param integer $count
     * @return \Ojs\JournalBundle\Entity\Sums
     */
    private function saveSum($em, $entityName, $count)
    {
        $check = $em->getRepository("OjsJournalBundle:Sums")->findOneBy(array('entity'=>$entityName));
        $sum = $check ? $check : (new Sums());
        $sum->setEntity($entityName);
        $sum->setSum($count);
        $em->persist($sum);
        $em->flush();
    }

    /**
     * 
     * @param Doctrine\ORM\EntityManager $em
     * @param string $entityName
     * @param OutputInterface $output
     * @return integer
     */
    private function countSaveEntity($em, $entityName, $output)
    {
        $output->write("<info>Counting " . $entityName . "</info> ");
        $count = $em->createQueryBuilder()
                        ->select('count(entity.id)')
                        ->from($entityName, 'entity')
                        ->getQuery()->getSingleScalarResult();
        $output->writeln(" <info> Result : " . $count . "</info>");
        $output->writeln("<info>Saving count result to OjsJournalBundle:Sums");
        $this->saveSum($em, $entityName, $count);

        return $count;
    }

}
