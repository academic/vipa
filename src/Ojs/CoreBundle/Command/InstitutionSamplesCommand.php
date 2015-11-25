<?php

namespace Ojs\CoreBundle\Command;

use Ojs\JournalBundle\Entity\Institution;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\InputArgument;

class InstitutionSamplesCommand extends ContainerAwareCommand
{
    /** @var EntityManager */
    private $em;

    protected function configure()
    {
        $this
            ->setName('ojs:institution:install:sample')
            ->setDefinition(
                array(
                    new InputArgument('filePath', InputArgument::REQUIRED, 'Csv File Path'),
                )
            )
            ->setDescription('Import institutions from csv file.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Creating sample institutions...');
        $getInstitutions = $this->getInstitutionsFromFile($input, $output);
        $findCountry = $this->em->getRepository('OkulBilisimLocationBundle:Country')->find(216);
        foreach($getInstitutions as $institutionName){
            $institutionName = trim($institutionName);
            $output->writeln($institutionName);

            $institution = new Institution();
            $institution->setName($institutionName);
            $institution->setCountry($findCountry);
            $this->em->persist($institution);
        }
        $this->em->flush();
    }

    private function getInstitutionsFromFile(InputInterface $input, OutputInterface $output)
    {
        $filePath = $input->getArgument('filePath');
        if(!file_exists($filePath)){
            throw new \InvalidArgumentException('file can not found!');
        }
        $file = fopen($filePath,"r");
        $getCsv = fgetcsv($file, 0, ',');
        return $getCsv;
    }
}
