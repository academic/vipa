<?php

namespace Ojs\JournalBundle\Form\Type;

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
            ->add('template', 'textarea', ['label' => 'mailtemplate.template', 'attr' => ['style' => 'height:200px']])
            ->add('type', 'text', ['label' => 'mailtemplate.type'])
            ->add('subject', 'text', ['label' => 'mailtemplate.subject'])
            ->add(
                'lang',
                'entity',
                [
                    'class' => 'Ojs\JournalBundle\Entity\Lang',
                    'property' => 'name',
                    'label' => 'mailtemplate.language',
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
                'data_class' => 'Ojs\JournalBundle\Entity\MailTemplate',
                'attr' => [
                    'novalidate' => 'novalidate',
                    'class' => 'form-validate',
                ],
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_mailtemplate';
    }
}
