<?php

namespace Ojs\JournalBundle\Form\Type\JournalSetup;

use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Step2 extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'issn',
                null,
                [
                    'attr' => [
                        'class' => 'validate[required]',
                    ],
                ]
            )
            ->add('eissn', null)
            ->add(
                'founded',
                'collot_datetime',
                array(

                    'date_format' => 'dd-MM-yyyy',
                    'pickerOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'startView' => 'month',
                        'minView' => 'month',
                        'todayBtn' => 'true',
                        'todayHighlight' => 'true',
                        'autoclose' => 'true',
                    ],
                )
            )
            ->add('footer_text', 'textarea')
            ->add('period')
            ->add(
                'country',
                'entity',
                [
                    'class' => 'BulutYazilim\LocationBundle\Entity\Country',
                    'attr' => [
                        'class' => 'select2-element',
                    ],
                ]
            )
            ->add(
                'Publisher',
                'entity',
                [
                    'class' => 'Ojs\JournalBundle\Entity\Publisher',
                    'attr' => [
                        'class' => 'select2-element',
                    ],
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'validation_groups' => ['setupStep2'],
                'data_class' => Journal::class,
                'attr' => [
                    'novalidate' => 'novalidate',
                    'class' => 'form-validate',
                ],
            )
        );
    }
}
