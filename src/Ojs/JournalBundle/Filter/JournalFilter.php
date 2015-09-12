<?php

namespace Ojs\JournalBundle\Filter;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Ojs\JournalBundle\Service\JournalService;

class JournalFilter extends SQLFilter
{
    protected $listener;
    protected $entityManager;
    protected $disabled = array();

    /** @var  JournalService */
    protected $journalService;

    /**
     * @return JournalService
     */
    public function getJournalService()
    {
        return $this->journalService;
    }

    /**
     * @param JournalService $journalService
     */
    public function setJournalService(JournalService $journalService)
    {
        $this->journalService = $journalService;
    }

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        $mappings = $targetEntity->getAssociationMappings();
        if(!array_key_exists('journal', $mappings) || $mappings['journal']['targetEntity'] !== 'Ojs\JournalBundle\Entity\Journal'){
            return '';
        }
        try {
            $selectedJournal = $this->journalService->getSelectedJournal();
        } catch (\Exception $e) {
            return '';
        }
        if(!$selectedJournal){
            return '';
        }
        $journalJoinColumn = $mappings['journal']['joinColumns'][0]['name'];

        $addCondSql = $targetTableAlias.'.'.$journalJoinColumn . ' = '.$selectedJournal->getId();

        return $addCondSql;
    }
}
