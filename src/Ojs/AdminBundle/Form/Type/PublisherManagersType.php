<?php

namespace Ojs\AdminBundle\Form\Type;

use Ojs\AdminBundle\Entity\PublisherManagers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublisherManagersType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'publisher',
                'entity',
                [
                    'class' => 'Ojs\JournalBundle\Entity\Publisher',
                    'label' => 'publisher',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                    'attr' => array("class" => "select2-element"),
                ]
            )
            ->add(
                'user',
                'entity',
                [
                    'class' => 'Ojs\UserBundle\Entity\User',
                    'label' => 'user',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                    'attr' => array("class" => "select2-element"),
                ]
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
                'data_class' => PublisherManagers::class,
                'cascade_validation' => true,
                'attr' => [
                    'class' => 'form-validate',
                ],
            )
        );
    }
}
