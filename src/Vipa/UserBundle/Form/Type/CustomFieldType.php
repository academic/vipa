<?php

namespace Vipa\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomFieldType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'label',
                'text',
                [
                    'attr' => [
                        'class' => 'validate[required,minSize[2]]',
                    ],
                ]
            )
            ->add(
                'value',
                'text',
                [
                    'attr' => [
                        'class' => 'validate[required,minSize[2]]',
                    ],
                ]
            )
            ->add('is_url')
            ->add('user_id', 'hidden', ['attr' => ['value' => $options['user']]]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Vipa\UserBundle\Entity\CustomField',
                'user' => 0,
                'csrf_protection' => false,
                'attr' => [
                    'class' => 'validate-form',
                    'novalidate' => 'novalidate',
                ],
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'vipa_userbundle_customfieldtype';
    }
}
