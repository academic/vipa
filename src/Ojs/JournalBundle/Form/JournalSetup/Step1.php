<?php

namespace Ojs\JournalBundle\Form\JournalSetup;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Step1 extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null,[
                'attr' => [
                    'class' => 'validate[required]'
                ]
            ])
            ->add('titleAbbr', null,[
                'attr' => [
                    'class' => 'validate[required]'
                ]
            ])
            ->add('subtitle', null,[
                'attr' => [
                    'class' => 'validate[required]'
                ]
            ])
            ->add('titleTransliterated');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\Journal'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_journal_setup_step1';
    }

}
