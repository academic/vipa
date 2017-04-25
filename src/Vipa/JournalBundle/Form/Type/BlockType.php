<?php

namespace Vipa\JournalBundle\Form\Type;

use Vipa\CoreBundle\Form\Type\JournalBasedTranslationsType;
use Vipa\JournalBundle\Entity\Block;
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
            ->add('translations', JournalBasedTranslationsType::class,[
                'label' => ' ',
                'fields' => [
                    'title' => [
                        'required' => false
                    ],
                    'content' => [
                        'required' => false,
                        'attr' => [
                            'class' => 'form-control wysihtml5'
                        ],
                        'field_type' => 'purified_textarea'
                    ]
                ]
            ])
            ->add('blockOrder')
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
                'data_class' => Block::class,
                'object_id' => null,
                'object_type' => null,
                'cascade_validation' => true,
                'attr' => [
                    'class' => 'form-validate',
                ],
            )
        );
    }
}
