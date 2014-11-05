<?php

namespace Ojstr\UserBundle\Form;

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
                ->add('userId')
                ->add('journalId')
                ->add('roleId', 'entity', array(
                    'class' => 'Ojstr\UserBundle\Entity\Role',
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
            'data_class' => 'Ojstr\UserBundle\Entity\UserJournalRole'
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
