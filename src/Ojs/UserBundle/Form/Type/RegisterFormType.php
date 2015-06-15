<?php

namespace Ojs\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegisterFormType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username',
                'text',
                array(
                    'label' => 'username',
                    'required' => true,
                )
            )
            ->add(
                'email',
                'email',
                array(
                    'label' => 'email',
                    'required' => true,
                )
            )
            ->add(
                'password',
                'repeated',
                array(
                    'label' => 'email',
                    'type' => 'password',
                    'required' => true,
                )
            )
            ->add(
                'firstName',
                'text',
                array(
                    'label' => 'firstname',
                    'required' => true,
                )
            )
            ->add(
                'lastName',
                'text',
                array(
                    'label' => 'lastname',
                    'required' => true,
                )
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\UserBundle\Entity\User',
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
        return 'ojs_user_register';
    }
}
