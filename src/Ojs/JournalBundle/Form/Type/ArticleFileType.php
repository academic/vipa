<?php

namespace Ojs\JournalBundle\Form\Type;

use Ojs\CoreBundle\Params\ArticleFileParams;
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
                ]
            )
            ->add('langCode', 'choice',
                [
                    'choices' => $options['locales'],
                ]
            )
            ->add('title', 'text')
            ->add('description', 'textarea');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Entity\ArticleFile',
                'cascade_validation' => true,
                'locales' => [],
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
        return 'ojs_journalbundle_articlefile';
    }
}
