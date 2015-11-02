<?php

namespace Ojs\JournalBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MinimalPublisherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', ['required' => false])
            ->add('publisherType', 'entity', [
                'required' => false,
                'empty_data'  => null,
                'placeholder' => 'none',
                'class' => 'Ojs\JournalBundle\Entity\PublisherTypes'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', 'Ojs\JournalBundle\Entity\Publisher');
    }

    public function getName()
    {
        return 'ojs_journal_minimalpublisher';
    }
}