<?php

namespace Ojs\JournalBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class ArticleAddAuthorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'author',
                'entity',
                [
                    'label' => 'author',
                    'class' => 'Ojs\JournalBundle\Entity\Author',
                    'attr' => array("class" => "select2-element"),
                    'query_builder' => function (EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('a')
                            ->leftJoin('OjsJournalBundle:ArticleAuthor', 'articleA', 'WITH', 'articleA.author = a.id')
                            ->where('articleA.article != :articleId')
                            ->having('COUNT(articleA.id) < 1')
                            ->groupBy('a.id')
                            ->setParameter('articleId', $options['articleId']);
                    }
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
                'validation_groups' => array('create'),
                'articleId' => null
            )
        );
    }
}
