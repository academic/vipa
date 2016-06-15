<?php

namespace Ojs\JournalBundle\Form\Type;

use Ojs\JournalBundle\Entity\JournalSubmissionFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class JournalSubmissionFileType
 * @package Ojs\JournalBundle\Form\Type
 */
class JournalSubmissionFileType extends AbstractType
{
    private $isArticleSubmissionStartProcess;

    public function __construct($isArticleSubmissionStartProcess = false)
    {
        $this->isArticleSubmissionStartProcess = $isArticleSubmissionStartProcess;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($this->isArticleSubmissionStartProcess){
            $builder->add('file', 'jb_file_ajax',
                array(
                    'endpoint' => 'submissionfiles'
                )
            );
            return;

        }
        $builder
            ->add('file', 'jb_file_ajax',
                array(
                    'endpoint' => 'submissionfiles'
                )
            )
            ->add('title', 'text', [
                'label' => 'submission_checklist.title'
                ]
            )
            ->add('detail', 'textarea', [
                'label' => 'submission_checklist.detail'
                ]
            )
            ->add(
                'locale',
                'choice',
                [
                    'choices' => $options['languages'],
                    'label' => 'languages'
                ]
            )
            ->add('visible', 'checkbox', [
                'label' => 'submission_checklist.visible',
                'required' => false
                ]
            )
            ->add('required', 'checkbox', [
                'label' => 'submission_checklist.required',
                'required' => false
                ]
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => JournalSubmissionFile::class,
                'cascade_validation' => true,
                'languages' => array(
                    array('tr' => 'Türkçe'),
                    array('en' => 'English'),
                ),
                'attr' => [
                    'novalidate' => 'novalidate',
                    'class' => 'form-validate',
                ],
            )
        );
    }
}
