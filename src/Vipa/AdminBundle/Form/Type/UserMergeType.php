<?php

namespace Vipa\AdminBundle\Form\Type;

use Vipa\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserMergeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if($options['primaryUser']){
            $builder->add('primaryUser', ChoiceType::class,
                [
                    'label' => 'user.merge.primary',
                    'choices' =>
                        [
                            $options['primaryUser']->getId() => $options['primaryUser']
                        ],
                    'disabled' => true
                ]);
        }else {
            $builder->add(
                'primaryUser',
                'tetranz_select2entity',
                [
                    'required' => true,
                    'label' => 'user.merge.primary',
                    'placeholder' => 'user',
                    'class' => 'Vipa\UserBundle\Entity\User',
                    'remote_route' => 'vipa_journal_user_search'
                ]
            );
        }

        $builder
            ->add(
                'slaveUsers',
                'tetranz_select2entity',
                [
                    'required' => true,
                    'multiple' => true,
                    'label' => 'user.merge.slave',
                    'placeholder' => 'user',
                    'class' => 'Vipa\UserBundle\Entity\User',
                    'remote_route' => 'vipa_journal_user_search'
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
                'primaryUser' => null
            )
        );
    }
}
