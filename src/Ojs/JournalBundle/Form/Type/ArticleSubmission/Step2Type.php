<?php

namespace Ojs\JournalBundle\Form\Type\ArticleSubmission;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Step2Type extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'articleType',
                'entity',
                array(
                    'label' => 'article.type',
                    'class' => 'Ojs\JournalBundle\Entity\ArticleTypes',
                    'required' => false
                )
            )
            ->add('translations', 'a2lix_translations_gedmo',[
                'locales' => $options['locales'],
                'translatable_class' => 'Ojs\JournalBundle\Entity\Article',
                'fields' => [
                    'title' => [
                        'field_type' => 'text'
                    ],
                    'titleTransliterated' => [
                        'field_type' => 'hidden'
                    ],
                    'subtitle' => [],
                    'subjects' => [
                        'label' => 'subjects',
                        'field_type' => 'tags'
                    ],
                    'keywords' => [
                        'label' => 'keywords',
                        'field_type' => 'tags'
                    ],
                    'abstract' => [
                        'required' => false,
                        'attr' => array('class' => ' form-control wysihtml5'),
                        'field_type' => 'textarea'
                    ]
                ]
            ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'locales' => [],
                'validation_groups' => ['setupStep1'],
                'data_class' => 'Ojs\JournalBundle\Entity\Article',
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
        return 'ojs_article_submission_step2';
    }
}
