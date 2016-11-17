<?php

namespace Ojs\JournalBundle\Form\Type;

use Ojs\CoreBundle\Form\Type\JournalBasedTranslationsType;
use Ojs\JournalBundle\Entity\Section;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', JournalBasedTranslationsType::class)
            ->add(
                'allowIndex',
                'checkbox',
                array(
                    'required' => false,
                    'label' => 'section.allow_index'
                )
            )
            ->add(
                'hideTitle',
                'checkbox',
                array(
                    'required' => false,
                    'label' => 'section.hide_title'
                )
            )
            ->add('sectionOrder', null, [
                'label' => 'section.order'
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
                'data_class' => Section::class,
                'cascade_validation' => true,
                'attr' => [
                    'class' => 'form-validate',
                ],
            )
        );
    }
}
