<?php

namespace Ojs\AdminBundle\Form\Type;

use Ojs\JournalBundle\Entity\JournalIndex;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournalIndexVerifyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('index', null, [
                'disabled' => true,
                'required' => false,
            ])
            ->add('link', null, [
                'disabled' => true,
                'required' => false,
            ])
            ->add('verified', 'checkbox', [
                'required' => false,
            ])
            ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => JournalIndex::class,
                'attr' => [
                    'class' => 'form-validate',
                ],
            )
        );
    }
}
