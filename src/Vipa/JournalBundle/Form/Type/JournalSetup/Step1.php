<?php

namespace Vipa\JournalBundle\Form\Type\JournalSetup;

use Vipa\JournalBundle\Entity\Journal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Step1 extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                null,
                [
                    'attr' => [
                        'class' => 'validate[required]',
                    ],
                ]
            )
            ->add(
                'titleAbbr',
                null,
                [
                    'attr' => [
                        'class' => 'validate[required]',
                    ],
                ]
            )
            ->add(
                'subtitle',
                null,
                [
                    'attr' => [
                        'class' => 'validate[required]',
                    ],
                ]
            )
            ->add('titleTransliterated');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'validation_groups' => ['setupStep1'],
                'data_class' => Journal::class,
                'attr' => [
                    'novalidate' => 'novalidate',
                    'class' => 'form-validate',
                ],
            )
        );
    }
}
