<?php

namespace Ojs\JournalBundle\Form\Type;

use Ojs\CoreBundle\Form\Type\JournalBasedTranslationsType;
use Ojs\JournalBundle\Entity\JournalPost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournalPostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', JournalBasedTranslationsType::class, array(
                    'fields' => array(
                        'title' => [],
                        'content' => array(
                            'required' => false,
                            'attr' => array('class' => ' form-control wysihtml5'),
                            'field_type' => 'purified_textarea'
                        )
                    )
                )
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => JournalPost::class,
                'cascade_validation' => true,
                'object' => null,
                'objectId' => null,
                'post_type' => 'default'
            )
        );
    }
}
