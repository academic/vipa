<?php

namespace Ojs\CoreBundle\Form\Type;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Service\JournalService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournalBasedTranslationsType extends AbstractType
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
        $journalLocaleBag = $journal->getLocaleCodeBag();
        if(!in_array($journal->getMandatoryLang()->getCode(), $journalLocaleBag)){
            $journalLocaleBag[] = $journal->getMandatoryLang()->getCode();
        }
        $resolver->setDefaults(array(
            'locales' => $journalLocaleBag,
            'default_locale' => $journal->getMandatoryLang()->getCode(),
            'required_locales' => [$journal->getMandatoryLang()->getCode()],
        ));
    }

    public function getParent()
    {
        return TranslationsType::class;
    }
}
