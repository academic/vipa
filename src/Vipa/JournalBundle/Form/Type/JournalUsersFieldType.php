<?php

namespace Vipa\JournalBundle\Form\Type;

use Vipa\JournalBundle\Service\JournalService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * Class JournalUsersFieldType
 * @package Vipa\JournalBundle\Form\Type
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
                'remote_route' => 'vipa_journal_user_search_based_journal',
                'remote_params' => array('journalId' => $this->journalService->getSelectedJournal()->getId()),
                'multiple' => true,
                'class' => 'VipaUserBundle:User'
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
