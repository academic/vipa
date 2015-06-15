<?php

namespace Ojs\JournalBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleAuthorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('authorOrder')
            ->add(
                'author',
                'entity',
                [
                    'class' => 'Ojs\JournalBundle\Entity\ArticleAuthor',
                ]
            )
            ->add(
                'article',
                'entity',
                [
                    'class' => 'Ojs\JournalBundle\Entity\Article',
                    'query_builder' => function (EntityRepository $er) use ($options) {
                        $qb = $er->createQueryBuilder('a');
                        $qb->where('a.journal = :journal');
                        $qb->setParameter('journal', $options['journal']);

                        return $qb;
                    },
                ]
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Entity\ArticleAuthor',
                'journal' => new Journal(),
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
        return 'ojs_journalbundle_articleauthor';
    }
}
