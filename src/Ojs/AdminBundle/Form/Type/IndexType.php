<?php

namespace Ojs\AdminBundle\Form\Type;

use Ojs\JournalBundle\Entity\Index;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IndexType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => 'name'))
            ->add('logo', 'jb_crop_image_ajax', array(
                'endpoint' => 'index',
                'img_width' => 200,
                'img_height' => 200,
                'crop_options' => array(
                    'aspect-ratio' => 200 / 200,
                    'maxSize' => "[200, 200]"
                )
            ))
            ->add('status', 'checkbox', ['label' => 'ojs.is_active', 'required' => false]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Index::class,
                'attr' => [
                    'class' => 'form-validate',
                ],
            )
        );
    }
}
