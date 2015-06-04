<?php

namespace Ojs\JournalBundle\Form;

use Ojs\Common\Params\ArticleFileParams;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleFileType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('title')
            ->add('keywords')
            ->add('description')
            ->add('type', 'choice', [
                'choices' => ArticleFileParams::$FILE_TYPES,
            ])
            ->add('article', 'autocomplete', [
                'class' => 'Ojs\JournalBundle\Entity\Article',
                'attr' => [
                    'class' => 'autocomplete',
                    'style' => 'width:100%',
                    'data-list' => $options['articlesEndPoint']."public/search/article",
                    'data-get' => $options['articleEndPoint']."public/article/get/",
                    "placeholder" => "type a journal name",
                ],
            ])
            ->add('version')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\ArticleFile',
            'articlesEndPoint' => '/',
            'articleEndPoint' => '/',
            'attr' => [
                'novalidate' => 'novalidate', 'class' => 'form-validate',
            ],
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_articlefile';
    }
}
