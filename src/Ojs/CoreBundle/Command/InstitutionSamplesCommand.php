<?php

namespace Ojs\CoreBundle\Command;

use Ojs\JournalBundle\Entity\Institution;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
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
            ->setName('ojs:install:samples:institution')
            ->setDefinition(
                array(
                    new InputArgument('filePath', InputArgument::REQUIRED, 'CSV file path'),
                )
            )
            ->setDescription('Import institutions from a CSV file.');
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
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Creating sample institutions...');
        $getInstitutions = $this->getInstitutionsFromFile($input, $output);
        $findCountry = $this->em->getRepository('BulutYazilimLocationBundle:Country')->find(216);

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
            throw new \InvalidArgumentException('Couldn\'t find the file!');
        }

        $file = fopen($filePath,"r");
        $getCsv = fgetcsv($file, 0, ',');
        return $getCsv;
    }
}
