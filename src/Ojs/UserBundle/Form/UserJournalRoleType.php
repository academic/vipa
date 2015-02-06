<?php

namespace Ojs\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserJournalRoleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('userId','entity',[
                    'class'=>'Ojs\UserBundle\Entity\User',
                    'attr' => array('class' => 'select2', 'style' => 'width:100%')

                ])
                ->add('journalId','entity',[
                    'class'=>'Ojs\JournalBundle\Entity\Journal',
                    'property'=>'title',
                    'attr' => array('class' => 'select2', 'style' => 'width:100%')

                ])
                ->add('roleId', 'entity', array(
                    'class' => 'Ojs\UserBundle\Entity\Role',
                    'property' => 'name',
                    'multiple' => false,
                    'expanded' => false,
                    'query_builder' => function (\Doctrine\ORM\EntityRepository $er) {
                return $er->createQueryBuilder('ujr')
                        ->where('ujr.isSystemRole = 0');
            },
                    'attr' => array("class" => "form-control"))
        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\UserBundle\Entity\UserJournalRole'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_userbundle_userjournalrole';
    }

}
