<?php

namespace Ojs\JournalBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournalRoleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'user',
            'autocomplete',
            [
                'class' => 'Ojs\UserBundle\Entity\User',
                'label' => 'user',
                'attr' => [
                    'class' => 'autocomplete',
                    'data-list' => $options['usersEndPoint'],
                    'data-get' => $options['userEndPoint'],
                    "placeholder" => "type a username",
                ],
            ]
        )
            ->add(
                'role',
                'entity',
                [
                    'label' => 'role.singular',
                    'class' => 'Ojs\UserBundle\Entity\Role',
                    'property' => 'name',
                    'multiple' => false,
                    'expanded' => false,
                    'attr' => array("class" => "select2-element"),
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
                'data_class' => 'Ojs\JournalBundle\Entity\JournalRole',
                'usersEndPoint' => '/',
                'userEndPoint' => '/',
                'attr' => [
                    'novalidate' => 'novalidate',
                    'class' => 'form-validate',
                ],
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_userbundle_userjournalrole';
    }
}
