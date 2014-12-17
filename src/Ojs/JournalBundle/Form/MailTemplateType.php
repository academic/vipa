<?php

namespace Ojs\JournalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MailTemplateType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('journal', 'entity', array(
                    'class' => 'Ojs\JournalBundle\Entity\Journal',
                    'property' => 'title',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false)
                )
                ->add('template')
                ->add('type')
                ->add('subject')
                ->add('lang')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\MailTemplate'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_mailtemplate';
    }

}
