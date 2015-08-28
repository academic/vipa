<?php

namespace Ojs\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InstitutionManagersType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'institution',
                'entity',
                [
                    'class' => 'Ojs\JournalBundle\Entity\Institution',
                    'label' => 'institution',
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
                'data_class' => 'Ojs\AdminBundle\Entity\InstitutionManagers',
                'cascade_validation' => true,
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
        return 'ojs_journalbundle_institutionmanagers';
    }
}
