<?php

namespace Ojs\JournalBundle\Form\Type;

use Ojs\JournalBundle\Entity\Publisher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MinimalPublisherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['required' => false, 'label' => 'publisher.name'])
            ->add('slug', TextType::class, ['required' => false, 'label' => 'publisher.slug'])
            ->add(
                'publisherType',
                EntityType::class,
                [
                    'required'    => false,
                    'empty_data'  => null,
                    'placeholder' => 'none',
                    'class'       => 'Ojs\JournalBundle\Entity\PublisherTypes',
                    'label'       => 'publisher.type',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Publisher::class);
    }
}
