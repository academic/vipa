<?php

namespace Ojstr\JournalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JournalSectionType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('title')
                ->add('allowIndex')
                ->add('hideTitle')
                ->add('journal', 'entity', array(
                    'class' => 'Ojstr\JournalBundle\Entity\Journal',
                    'property' => 'title',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true
                        )
                )
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Ojstr\JournalBundle\Entity\JournalSection'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'ojstr_journalbundle_journalsection';
    }

}
