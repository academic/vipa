<?php

namespace Vipa\CoreBundle\Form\Type;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Vipa\JournalBundle\Entity\Journal;
use Vipa\JournalBundle\Service\JournalService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournalLangCodeType extends AbstractType
{
    /**
     * @var JournalService
     */
    private $journalService;

    /**
     * JournalBasedTranslationsType constructor.
     *
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
        $journal = $this->journalService->getSelectedJournal(false);
        if(!$journal instanceof Journal){
            return;
        }
        $resolver->setDefaults(array(
            'choices' => $journal->getLocaleCodeBag(),
        ));
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
