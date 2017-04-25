<?php

namespace Vipa\AdminBundle\Form\Type;

use Vipa\JournalBundle\Entity\PublisherDesign;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublisherDesignType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'publisher',
                'entity',
                array(
                    'label' => 'publisher',
                    'class' => 'Vipa\JournalBundle\Entity\Publisher',
                    'property' => 'name',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'error_bubbling' => true,
                )
            )
            ->add(
                'title',
                'text',
                [
                    'label' => 'Title'
                ]
            )
            ->add('editableContent', 'hidden')
            ->add('public', 'checkbox',
                [
                    'label' => 'vipa.is_public',
                    'required' => false
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
                'data_class' => PublisherDesign::class,
                'attr' => [
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
        return 'vipa_adminbundle_publisher_design';
    }
}
