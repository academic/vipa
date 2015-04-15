<?php

namespace Ojs\JournalBundle\Form\JournalSetup;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Step3 extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('languages', 'entity', array(
                    'class' => 'Ojs\JournalBundle\Entity\Lang',
                    'property' => 'name',
                    'multiple' => true,
                    'expanded' => false,
                    'attr'=>[
                        'class'=>'validate[required]'
                        ]
                    )
                )
                ->add('subjects','entity',[
                    'class'=>'Ojs\JournalBundle\Entity\Subject',
                    'property'=>'subject',
                    'multiple'=>true,
                    'attr'=>[
                        'class'=>'select2-element'
                    ]
                ])
                ->add('submitRoles', 'entity', array(
                        'class' => 'Ojs\UserBundle\Entity\Role',
                        'property' => 'name',
                        'multiple' => true,
                        'attr'=>[
                            'class'=>'select2-element'
                        ]
                    )
                )
                ->add('domain')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\Journal',
            'attr'=>[
                'novalidate'=>'novalidate'
,'class'=>'form-validate'
            ]
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'ojs_journalbundle_journal';
    }

}
