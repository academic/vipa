<?php

namespace Ojstr\JournalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JournalType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('title')
                ->add('titleAbbr')
                ->add('titleTransliterated')
                ->add('languages', 'entity', array(
                    'class' => 'Ojstr\JournalBundle\Entity\Lang',
                    'property' => 'name',
                    'multiple' => true,
                    'expanded' => false,
                    'required' => false
                        )
                )
                ->add('subtitle')
                ->add('issn')
                ->add('eissn')
                ->add('firstPublishDate')
                ->add('period')
                ->add('url')
                ->add('country')
                ->add('publishStatus')
                ->add('status')
                ->add('image')
                ->add('scope')
                ->add('mission')
                ->add('themeId')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Ojstr\JournalBundle\Entity\Journal'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'ojstr_journalbundle_journal';
    }

}
