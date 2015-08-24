<?php

namespace Ojs\JournalBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', 'a2lix_translations')
            ->add('volume', 'text', array('label' => 'volume'))
            ->add('number', 'text', array('label' => 'number'))
            ->add('special', 'checkbox', array(
                'label' => 'special',
                'required' => false,
                )
            )
            ->add('supplement', 'checkbox', array(
                'label' => 'supplement',
                'required' => false,
                )
            )
            ->add('year', 'text', array('label' => 'year'))
            ->add(
                'datePublished',
                'collot_datetime',
                array(

                    'date_format' => 'dd-MM-yyyy',
                    'pickerOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'startView' => 'month',
                        'minView' => 'month',
                        'todayBtn' => 'true',
                        'todayHighlight' => 'true',
                        'autoclose' => 'true',
                    ],
                )
            )
            ->add('tags', 'tags')
            ->add('published', 'checkbox', ['label' => 'published'])
            ->add('full_file', 'jb_file_ajax', array(
                'endpoint' => 'issuefiles'
            ))
            ->add('cover', 'jb_crop_image_ajax', array(
                'endpoint' => 'journal',
                'img_width' => 200,
                'img_height' => 300,
                'crop_options' => array(
                    'aspect-ratio' => 200 / 300,
                    'maxSize' => "[200, 300]"
                )
            ))
            ->add('header', 'jb_crop_image_ajax', array(
                'endpoint' => 'journal',
                'img_width' => 960,
                'img_height' => 200,
                'crop_options' => array(
                    'aspect-ratio' => 960 / 200,
                    'maxSize' => "[960, 200]"
                )
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Entity\Issue',
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
        return 'ojs_journalbundle_issue';
    }
}
