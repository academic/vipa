<?php

namespace Vipa\JournalBundle\Form\Type;

use Vipa\CoreBundle\Form\Type\JournalBasedTranslationsType;
use Vipa\JournalBundle\Entity\SubmissionSetting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubmissionSettingType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('submissionEnabled', 'checkbox', [
                'required' => false,
                'label' => 'journal.settings.submission.open',
            ])
            ->add('translations', JournalBasedTranslationsType::class, [
                'fields' => [
                    'submissionCloseText' => [
                        'required' => false,
                        'label' => 'journal.settings.submission.close.text',
                        'attr' => [
                            'class' => 'wysihtml5 submission-close-text',
                        ],
                        'field_type' => 'purified_textarea',
                    ],
                    'submissionAbstractTemplate' => [
                        'required' => false,
                        'label' => 'journal.settings.submission.abstractTemplate',
                        'attr' => [
                            'class' => 'wysihtml5 submission-abstract-template',
                        ],
                        'field_type' => 'purified_textarea',
                    ],
                    'submissionConfirmText' => [
                        'required' => false,
                        'label' => 'journal.settings.last.step.confirm',
                        'attr' => [
                            'class' => 'last-step-confirm'
                        ]
                    ],
                ]
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => SubmissionSetting::class,
                'cascade_validation' => true,
            )
        );
    }
}
