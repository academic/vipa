<?php

namespace Ojstr\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('password', 'password', array('attr' => array('style' => 'color:#898989;font-size:80%')))
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('isActive')
            //->add('avatar', 'file')
            ->add('status')
            ->add('roles', 'entity', array(
                'class' => 'Ojstr\UserBundle\Entity\Role',
                'property' => 'name',
                'multiple' => true,
                'expanded' => false,
                'attr' => array('class' => 'select2', 'style' => 'width:200px')
            ))
            ->add('subjects', 'entity', array(
                'class' => 'Ojstr\JournalBundle\Entity\Subject',
                'property' => 'subject',
                'multiple' => true,
                'expanded' => false,
                'attr' => array('class' => 'select2', 'style' => 'width:200px')
            ))
//                ->add('journals', 'entity', array(
//                    'class' => 'Ojstr\JournalBundle\Entity\Journal',
//                    'property' => 'title',
//                    'multiple' => true,
//                    'expanded' => false,
//                ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojstr\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojstr_userbundle_user';
    }

}
