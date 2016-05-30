<?php

namespace Ojs\JournalBundle\Form\Type;

use Ojs\JournalBundle\Entity\Board;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BoardType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', 'a2lix_translations',[
                'fields' => [
                    'name' => [
                        'label' => 'board.name'
                    ],
                    'description' => [
                        'required' => false
                    ],
                ]
            ])
            ->add('boardOrder', 'integer', [
                'label' => 'order',
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Board::class,
                'cascade_validation' => true,
            )
        );
    }
}
