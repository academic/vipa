<?php

namespace Ojs\JournalBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class JournalUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'user',
                'entity',
                [
                    'label' => 'user',
                    'class' => 'Ojs\UserBundle\Entity\User',
                    'property' => 'fullname',
                    'multiple' => false,
                    'expanded' => false,
                    'attr' => array("class" => "select2-element"),
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('journal_user');
                    },
                ]
            )
            ->add(
                'roles',
                'entity',
                [
                    'label' => 'roles',
                    'class' => 'Ojs\UserBundle\Entity\Role',
                    'property' => 'name',
                    'multiple' => true,
                    'expanded' => false,
                    'attr' => array("class" => "select2-element"),
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('journal_user_role');
                    },
                ]
            );
    }

    /**
     * Returns the name of this type.
     * @return string The name of this type
     */
    public function getName()
    {
        return 'ojs_journalbundle_journaluser';
    }

}