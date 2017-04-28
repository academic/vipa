<?php

namespace Vipa\JournalBundle\Filter;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Vipa\JournalBundle\Service\JournalService;

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
        if(!array_key_exists('journal', $mappings) || $mappings['journal']['targetEntity'] !== 'Vipa\JournalBundle\Entity\Journal'){
            return '';
        }
        //return if journal filter disabled globally for current entity
        if(isset($GLOBALS[$targetEntity->getName().'#journalFilter']) && $GLOBALS[$targetEntity->getName().'#journalFilter'] == false){
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
