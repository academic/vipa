<?php

namespace Vipa\AdminBundle\Form\Type;

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
            ->add('template', 'textarea', [
                    'label' => 'mailtemplate.template',
                    'attr' => [
                        'class' => 'form-control wysihtml5',
                    ]
                ]
            )
            ->add('subject', 'text', [
                'label' => 'mailtemplate.subject',
            ])
            ->add('active')
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
                'institution' => null,
                'attr' => [
                    'novalidate' => 'novalidate',
                    'class' => 'validate-form',
                ],
            )
        );
    }
}
