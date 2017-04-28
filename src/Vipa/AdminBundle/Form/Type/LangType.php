<?php

namespace Vipa\AdminBundle\Form\Type;

use Vipa\JournalBundle\Entity\Lang;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LangType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', 'text', ['attr' => ['placeholder' => 'Do not use special characters'], 'label' => 'lang.code'])
            ->add('name', 'text', ['label' => 'lang.name'])
            ->add('rtl', 'checkbox', [
                'label' => 'lang.rtl',
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
                'data_class' => Lang::class,
                'cascade_validation' => true,
                'attr' => [
                    'class' => 'form-validate',
                ],
            )
        );
    }
}
