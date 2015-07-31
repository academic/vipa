<?php

namespace Ojs\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add(
                'type',
                'choice',
                [
                    'label' => 'Type',
                    'choices' => [
                        'html' => 'HTML Content',
                        'link' => 'Link List',
                    ],
                ]
            )
            ->add(
                'content',
                'textarea',
                [
                    'label' => 'Content',
                    'required' => false,
                ]
            )
            ->add('objectId', 'hidden', ['data' => $options['object_id']])
            ->add('objectType', 'hidden', ['data' => $options['object_type']])
            ->add(
                'color',
                'choice',
                [
                    'label' => 'Block Color',
                    'choices' => [
                        'default' => 'Grey',
                        'primary' => 'Blue',
                        'success' => 'Green',
                        'info' => 'Light Blue',
                        'warning' => 'Yellow',
                        'danger' => 'Red',

                    ],
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
                'data_class' => 'Ojs\SiteBundle\Entity\Block',
                'object_id' => null,
                'object_type' => null,
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
        return 'ojs_sitebundle_block';
    }
}
