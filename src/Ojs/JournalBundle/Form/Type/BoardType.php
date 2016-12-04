<?php

namespace Ojs\JournalBundle\Form\Type;

use Ojs\CoreBundle\Form\Type\JournalBasedTranslationsType;
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
            ->add('translations', JournalBasedTranslationsType::class,[
                'fields' => [
                    'name' => [
                        'label' => 'board.name'
                    ],
                    'description' => [
                        'attr' => array('class' => ' form-control wysihtml5'),
                        'field_type' => 'purified_textarea',
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
