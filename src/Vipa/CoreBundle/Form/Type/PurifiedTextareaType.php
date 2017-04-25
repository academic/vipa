<?php

namespace Vipa\CoreBundle\Form\Type;

use Vipa\CoreBundle\Form\DataTransformer\PurifiedTextareaTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PurifiedTextareaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new PurifiedTextareaTransformer(), true);
    }

    public function getParent()
    {
        return 'textarea';
    }

    public function setDefault(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'compound' => false,
        ));
    }

    public function getName()
    {
        return 'purified_textarea';
    }
}