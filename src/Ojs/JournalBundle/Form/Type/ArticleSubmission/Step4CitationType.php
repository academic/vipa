<?php

namespace Ojs\JournalBundle\Form\Type\ArticleSubmission;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Step4CitationType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'hidden')
            ->add('raw')
            ->add('type','choice',[
                'choices' => [
                    $options['citationTypes']
                ]
            ])
            ->add('orderNum');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Entity\Citation',
                'citationTypes' => [],
                'attr' => [
                    'novalidate' => 'novalidate',
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
        return 'article_submission_citation';
    }
}
