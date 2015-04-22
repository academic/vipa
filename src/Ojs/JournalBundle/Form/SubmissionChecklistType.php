<?php

namespace Ojs\JournalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class SubmissionChecklistType
 * @package Ojs\JournalBundle\Form
 */
class SubmissionChecklistType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('label', 'text', ['label' => 'submission_checklist.label'])
                ->add('detail', 'textarea', ['label' => 'submission_checklist.detail'])
                ->add('visible', 'checkbox', ['label' => 'submission_checklist.visible'])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\SubmissionChecklist',
            'attr' => [
                'novalidate' => 'novalidate'
                , 'class' => 'form-validate'
            ]
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_submissionchecklist';
    }

}
