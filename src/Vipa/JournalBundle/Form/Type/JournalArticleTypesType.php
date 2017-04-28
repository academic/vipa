<?php

namespace Vipa\JournalBundle\Form\Type;
use Vipa\JournalBundle\Entity\Journal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournalArticleTypesType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'articleTypes',
                'entity',
                array(
                    'class' => 'VipaJournalBundle:ArticleTypes',
                    'multiple' => true,
                    'required' => true,
                    'label' => 'article.types',
                    'attr' => [
                        'style' => 'height: 200px',
                    ]
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
                'data_class' => Journal::class,
                'cascade_validation' => true
            )
        );
    }
}
