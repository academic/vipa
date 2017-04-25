<?php

namespace Vipa\JournalBundle\Form\Type;

use Vipa\CoreBundle\Form\Type\JournalLangCodeType;
use Vipa\CoreBundle\Params\ArticleFileParams;
use Vipa\JournalBundle\Entity\ArticleFile;
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
        $fileTypes = ArticleFileParams::$FILE_TYPES;
        array_shift($fileTypes);
        $builder
            ->add('file', 'jb_file_ajax', array(
                'endpoint' => 'articlefiles'
            ))
            ->add('type', 'choice',
                [
                    'choices' => $fileTypes,
                    'label' => 'articlefile.type'
                ]
            )
            ->add('langCode', JournalLangCodeType::class,
                [
                    'label' => 'articlefile.langcode'
                ]
            )
            ->add('title', 'text', ['label' => 'articlefile.title'])
            ->add('description', 'textarea', [
                'required' => false
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => ArticleFile::class,
                'cascade_validation' => true,
                'attr' => [
                    'class' => 'form-validate',
                ],
            )
        );
    }
}
