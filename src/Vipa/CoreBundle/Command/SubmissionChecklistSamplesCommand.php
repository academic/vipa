<?php

namespace Vipa\CoreBundle\Command;

use Vipa\JournalBundle\Entity\Journal;
use Vipa\JournalBundle\Entity\SubmissionChecklist;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;

class SubmissionChecklistSamplesCommand extends ContainerAwareCommand
{
    /** @var EntityManager */
    private $em;

    protected function configure()
    {
        $this
            ->setName('vipa:sync:submission-checklist')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Remove submission checklist if exists')
            ->setDescription('Creates sample submission checklists')
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
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Creating sample submission checklists...');
        $allJournals = $this->getAllJournals();

        foreach($allJournals as $journal){
            if($journal->getSubmissionChecklist()->count()> 0){
                if(!$input->getOption('force')){
                    $output->writeln('Submission checklists already exists');
                    continue;
                }
            }
            if($input->getOption('force')){
                $this->clearSubmissionChecklists($journal);
            }
            $output->writeln('Creating a checklist for '. $journal->getTitle());
            $this->createItem1($journal, $output);
            $this->createItem2($journal, $output);
            $this->createItem3($journal, $output);
            $output->writeln('');
            $output->writeln('');
        }
    }

    private function getAllJournals()
    {
        return $this->em->getRepository('VipaJournalBundle:Journal')->findAll();
    }

    /**
     * @param Journal $journal
     * @param OutputInterface $output
     */
    private function createItem1(Journal $journal, OutputInterface $output)
    {
        $submissionChecklistTr = new SubmissionChecklist();
        $submissionChecklistTr
            ->setJournal($journal)
            ->setLocale('tr')
            ->setLabel('İlk sayfada gerekli bilgiler bulunmalıdır. ')
            ->setVisible(true)
            ->setDetail('<ul>
                            <li>Yazarların adı</li>
                            <li>Açıklayıcı ve bilgilendirici makale başlığı</li>
                            <li>Yazarların email adresi, telefon numarası</li>
                        </ul>');

        $this->em->persist($submissionChecklistTr);

        $submissionChecklistEn = new SubmissionChecklist();
        $submissionChecklistEn->setJournal($journal)
            ->setLocale('en')
            ->setLabel('The title page should include necessary information.')
            ->setVisible(true)
            ->setDetail('<ul>
                            <li>The name(s) of the author(s)</li>
                            <li>A concise and informative title</li>
                            <li>The e-mail address, telephone number of the corresponding author</li>
                        </ul>');

        $this->em->persist($submissionChecklistEn);
        $this->em->flush();

        $output->writeln('Persisted the first item.');
    }

    /**
     * @param Journal $journal
     * @param OutputInterface $output
     */
    private function createItem2(Journal $journal, OutputInterface $output)
    {
        $submissionChecklistTr = new SubmissionChecklist();
        $submissionChecklistTr
            ->setJournal($journal)
            ->setLocale('tr')
            ->setLabel('Makale gönderimi tüm makale yazarları tarafından onaylanmalıdır. ')
            ->setVisible(true)
            ->setDetail('<ul>
                            <li>Makalenin son halini tüm makalenin yazarlarının okumuş ve onaylamış olmalısı gereklidir.</li>
                        </ul>');

        $this->em->persist($submissionChecklistTr);

        $submissionChecklistEn = new SubmissionChecklist();
        $submissionChecklistEn->setJournal($journal)
            ->setLocale('en')
            ->setLabel('Manuscript must be approved.')
            ->setVisible(true)
            ->setDetail('<ul>
                            <li>All authors must have read and approved the most recent version of the manuscript.</li>
                        </ul>');

        $this->em->persist($submissionChecklistEn);
        $this->em->flush();

        $output->writeln('Persisted the second item.');
    }

    /**
     * @param Journal $journal
     * @param OutputInterface $output
     */
    private function createItem3(Journal $journal, OutputInterface $output)
    {
        $submissionChecklistTr = new SubmissionChecklist();
        $submissionChecklistTr
            ->setJournal($journal)
            ->setLocale('tr')
            ->setLabel('Makale <i>yazım kurallarına</i> dikkat edilerek yazılmış olmalıdır.')
            ->setVisible(true)
            ->setDetail('<ul>
                            <li>Makalenin son hali yazım kurallarına uygun olarak gözden geçirilmiş olmalıdır.</li>
                        </ul>');

        $this->em->persist($submissionChecklistTr);

        $submissionChecklistEn = new SubmissionChecklist();
        $submissionChecklistEn->setJournal($journal)
            ->setLocale('en')
            ->setLabel('Manuscript must be <i>spell checked</i>.')
            ->setVisible(true)
            ->setDetail('<ul>
                            <li>The most recent version of the manuscript must be spell checked.</li>
                        </ul>');
        $this->em->persist($submissionChecklistEn);
        $this->em->flush();

        $output->writeln('Persisted the third item.');
    }

    /**
     * @param Journal $journal
     * @return bool
     */
    private function clearSubmissionChecklists(Journal $journal)
    {
        $submissionChecklists = $this->em->getRepository('VipaJournalBundle:SubmissionChecklist')->findBy([
            'journal' => $journal
        ]);

        if(count($submissionChecklists)>0){
            foreach($submissionChecklists as $submissionChecklist){
                $this->em->remove($submissionChecklist);
            }
            $this->em->flush();
        }

        return true;
    }
}
