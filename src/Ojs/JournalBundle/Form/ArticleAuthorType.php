<?php

namespace Ojs\JournalBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleAuthorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $journal = $options['journal_id'];
        $builder
            ->add('authorOrder')
            ->add('author', 'entity', [
                'class' => 'Ojs\JournalBundle\Entity\ArticleAuthor'
            ])
            ->add('article', 'entity', [
                'class' => 'Ojs\JournalBundle\Entity\Article',
                'query_builder' => function (EntityRepository $er) use ($journal) {
                    $qb = $er->createQueryBuilder('a');
                    $qb->where(
                        $qb->expr()->eq('a.journalId', ':journal')
                    );
                    $qb->setParameter('journal', $journal);
                    return $qb;
                }
            ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\ArticleAuthor',
            'journal_id' => 0
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_articleauthor';
    }
}
