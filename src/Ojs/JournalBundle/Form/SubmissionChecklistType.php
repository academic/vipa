<?php

namespace Ojs\JournalBundle\Form;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class SubmissionChecklistType
 * @package Ojs\JournalBundle\Form
 */
class SubmissionChecklistType extends AbstractType
{

    private $container;

    public function __construct(ContainerInterface $servicecontainer)
    {
        $this->container = $servicecontainer;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $languages = $this->container->getParameter('languages');
        $langs = [];
        foreach ($languages as $key => $lang) {
            $langs[$lang['code']] = $lang['name'];
        }
        $builder
            ->add('label', 'text', ['label' => 'submission_checklist.label'])
            ->add('detail', 'textarea', ['label' => 'submission_checklist.detail'])
            ->add('locale', 'choice', [
                'choices' => $langs
            ])
            ->add('visible', 'checkbox', ['label' => 'submission_checklist.visible']);
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
