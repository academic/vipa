<?php

namespace Ojs\JournalBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleSubmissionType extends AbstractType
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
            ])

            ->add('citations', 'collection', array(
                    'type' => new CitationType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'options' => array(
                        'citationTypes' => $options['citationTypes']
                    )
                )
            )
            ->add('articleFiles', 'collection', array(
                    'type' => new ArticleFileType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'options' => array(
                        'locales' => $options['locales']
                    )
                )
            )
            ->add('articleAuthors', 'collection', array(
                    'type' => new ArticleAuthorType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                )
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
                'locales' => [],
                'data_class' => 'Ojs\JournalBundle\Entity\Article',
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
        return 'ojs_article_submission';
    }
}
