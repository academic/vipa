<?php

namespace Ojs\JournalBundle\Form\Type;

use Ojs\Common\Params\ArticleFileParams;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFileType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'jb_file_ajax', array(
                'endpoint' => 'articlefiles'
            ))
            ->add('type', 'choice',
                [
                    'choices' => ArticleFileParams::$FILE_TYPES,
                ]
            )
            ->add('version')
            ->add('translations', 'a2lix_translations_gedmo',[
                'translatable_class' => 'Ojs\JournalBundle\Entity\ArticleFile',
                'fields' => [
                    'title' => [],
                    'description' => [],
                    'keywords' => [
                        'label' => 'keywords',
                        'field_type' => 'tags'
                    ]
                ]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Entity\ArticleFile',
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
        return 'ojs_journalbundle_articlefile';
    }
}
