<?php

namespace Ojs\JournalBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                'tetranz_select2entity',
                [
                    'required' => true,
                    'label' => 'authors',
                    'placeholder' => 'authors',
                    'class' => 'Ojs\JournalBundle\Entity\Author',
                    'remote_route' => 'ojs_journal_article_author_search',
                    'remote_params' => array('journalId' => $options['journalId'])
                ]
            )->add('authorOrder', null, ['label' => 'author.order']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'validation_groups' => array('create'),
                'journalId' => null
            )
        );
    }
}
