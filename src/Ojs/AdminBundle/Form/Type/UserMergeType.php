<?php

namespace Ojs\AdminBundle\Form\Type;

use Ojs\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
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
        $builder
            ->add(
                'primaryUser',
                'tetranz_select2entity',
                [
                    'required' => true,
                    'label' => 'user.merge.primary',
                    'placeholder' => 'user',
                    'class' => 'Ojs\UserBundle\Entity\User',
                    'remote_route' => 'ojs_journal_user_search'
                ]
            )
            ->add(
                'slaveUsers',
                'tetranz_select2entity',
                [
                    'required' => true,
                    'multiple' => true,
                    'label' => 'user.merge.slave',
                    'placeholder' => 'user',
                    'class' => 'Ojs\UserBundle\Entity\User',
                    'remote_route' => 'ojs_journal_user_search'
                ]
            );
    }
}
