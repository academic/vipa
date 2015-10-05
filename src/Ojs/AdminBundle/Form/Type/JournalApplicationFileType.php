<?php

namespace Ojs\AdminBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Ojs\JournalBundle\Form\Type\JournalContactType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournalApplicationFileType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
                ]
            )
            ->add('visible', 'checkbox', [
                    'label' => 'submission_checklist.visible',
                    'required' => false
                ]
            )
            ->add('required', 'checkbox', [
                    'label' => 'application_checklist.required',
                    'required' => false
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
                'data_class' => 'Ojs\JournalBundle\Entity\JournalApplicationFile',
                'cascade_validation' => true,
                'languages' => [],
                'attr' => [
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
        return 'ojs_journalbundle_journalapplication';
    }
}
