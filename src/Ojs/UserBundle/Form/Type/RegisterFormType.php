<?php

namespace Ojs\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterFormType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                null,
                array(
                    'label' => 'user.title',
                    'required' => false,
                )
            )
            ->add(
                'firstName',
                'text',
                array(
                    'label' => 'user.register.firstname',
                    'required' => true,
                )
            )
            ->add(
                'lastName',
                'text',
                array(
                    'label' => 'user.register.lastname',
                    'required' => true,
                )
            )
            ->add(
                'username',
                'text',
                array(
                    'label' => 'user.register.username',
                    'required' => true,
                )
            )
            ->add(
                'email',
                'email',
                array(
                    'label' => 'user.register.email',
                    'required' => true,
                )
            )
            ->add(
                'plain_password',
                'repeated',
                array(
                    'first_options' => [
                        'label' => 'user.register.password.first'
                    ],
                    'second_options' => [
                        'label' => 'user.register.password.second'
                    ],
                    'type' => 'password',
                    'required' => true,
                )
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\UserBundle\Entity\User',
                'cascade_validation' => true,
                'validation_groups' => ['ojs_register'],
                'attr' => [
                    'class' => 'form-validate',
                ],
                'translation_domain' => 'forms'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_user_register';
    }
}
