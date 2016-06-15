<?php

namespace Ojs\JournalBundle\Form\Type;

use Ojs\CoreBundle\Form\Type\JournalBasedTranslationsType;
use Ojs\JournalBundle\Entity\JournalPage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournalPageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', JournalBasedTranslationsType::class, array(
                'fields' => array(
                    'title' => [],
                    'body' => array(
                        'required' => false,
                        'attr' => array('class' => ' form-control wysihtml5'),
                        'field_type' => 'purified_textarea'
                        )
                    )
                )
            )
            ->add('visible', 'checkbox', [
                'required' => false
            ])
            ->add('tags', 'tags', [
                'required' => true,
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => JournalPage::class,
                'cascade_validation' => true,
                'attr' => [
                    'class' => 'form-validate',
                ],
            )
        );
    }
}
