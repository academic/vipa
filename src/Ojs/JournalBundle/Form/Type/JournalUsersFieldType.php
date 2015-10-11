<?php

namespace Ojs\JournalBundle\Form\Type;

use Ojs\JournalBundle\Service\JournalService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * Class JournalUsersFieldType
 * @package Ojs\JournalBundle\Form\Type
 */
class JournalUsersFieldType extends AbstractType
{
    /**
     * @var JournalService
     */
    private $journalService;

    /**
     * JournalUsersFieldType constructor.
     * @param JournalService $journalService
     */
    public function __construct(JournalService $journalService)
    {
        $this->journalService = $journalService;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'remote_route' => 'search_journal_users',
                'remote_params' => array('journalId' => $this->journalService->getSelectedJournal()->getId()),
                'multiple' => true,
                'class' => 'OjsUserBundle:User'
            )
        );
    }

    public function getName()
    {
        return 'journal_users_type';
    }

    public function getParent()
    {
        return 'tetranz_select2entity';
    }
}
