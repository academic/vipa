<?php

namespace Vipa\JournalBundle\Form\Type;

use Vipa\JournalBundle\Entity\SubmissionChecklist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SubmissionChecklistType
 * @package Vipa\JournalBundle\Form\Type
 */
class SubmissionChecklistType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', 'text', ['label' => 'submission_checklist.label'])
            ->add('detail', 'purified_textarea', [
                'required' => false,
                'label' => 'submission_checklist.detail',
                'attr' => array('class' => ' form-control wysihtml5'),
            ])
            ->add(
                'locale',
                'choice',
                [
                    'choices' => $options['languages'],
                    'label' => 'languages'
                ]
            )
            ->add('visible', 'checkbox', [
                'required' => false,
                'label' => 'submission_checklist.visible'
            ])
            ->add('order', null, [
                    'label' => 'order'
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
                'data_class' => SubmissionChecklist::class,
                'cascade_validation' => true,
                'languages' => array(
                    array('tr' => 'Türkçe'),
                    array('en' => 'English'),
                ),
                'attr' => [
                    'class' => 'form-validate',
                ],
            )
        );
    }
}
