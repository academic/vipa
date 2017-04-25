<?php

namespace Vipa\JournalBundle\Form\Type;

use Vipa\JournalBundle\Entity\MailTemplate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MailTemplateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('useJournalDefault', null, [
                'label' => 'use.default.template'
            ])
            ->add('active', null, [
                'attr' => [
                    'class' => 'use-default-hidden'
                ]
            ])
            ->add('template', 'textarea', [
                'label' => 'mailtemplate.template',
                'attr' => [
                    'class' => 'form-control wysihtml5 use-default-hidden',
                    ]
                ]
            )
            ->add('subject', 'text', [
                    'label' => 'mailtemplate.subject',
                    'required' => true,
                    'attr' => [
                        'class' => ' use-default-hidden'
                    ],
                ]
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => MailTemplate::class,
                'cascade_validation' => true,
                'attr' => [
                    'class' => 'form-validate',
                ],
            )
        );
    }
}
