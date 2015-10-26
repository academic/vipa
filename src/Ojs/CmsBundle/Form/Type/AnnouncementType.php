<?php

namespace Ojs\CmsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnouncementType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('content', 'textarea', array(
                    'required' => false,
                    'attr' => array('class' => ' form-control wysihtml5')
                )
            )
            ->add('image', 'jb_crop_image_ajax', array(
                'endpoint' => 'announcement',
                'img_width' => 128,
                'img_height' => 128,
                'crop_options' => array(
                    'aspect-ratio' => 128 / 128,
                    'maxSize' => "[128, 128]"
                )
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Ojs\CmsBundle\Entity\Announcement',
            'cascade_validation' => true
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_cms_announcement';
    }
}