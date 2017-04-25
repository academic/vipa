<?php

namespace Vipa\JournalBundle\Form\Type;

use Vipa\JournalBundle\Entity\Citation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CitationType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('raw', 'textarea', ['label' => 'citation.citation'])
            ->add('type','choice',[
                'choices' => [
                    $options['citationTypes']
                ],
                'label' => 'citation.type'
            ])
            ->add('orderNum', null, ['label' => 'citation.id']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Citation::class,
                'cascade_validation' => true,
                'citationTypes' => [],
                'attr' => [
                    'class' => 'form-validate',
                ],
            )
        );
    }
}
