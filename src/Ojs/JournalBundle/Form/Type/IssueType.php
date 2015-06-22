<?php

namespace Ojs\JournalBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IssueType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('volume', 'text', array('label' => 'volume'))
            ->add('number', 'text', array('label' => 'number'))
            ->add('special', 'checkbox', array('label' => 'special'))
            ->add('supplement', 'checkbox', array('label' => 'supplement'))
            ->add('title', 'text', array('label' => 'title'))
            ->add('description', 'text', array('label' => 'description'))
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
            ->add(
                'tags',
                'text',
                array(
                    'label' => 'tags',
                    'attr' => [
                        'class' => ' form-control input-xxl',
                        'data-role' => 'tagsinputautocomplete',
                        'placeholder' => 'Comma-seperated tag list',
                        'data-list' => $options['tagEndPoint'],
                    ],
                )
            )
            ->add('published', 'checkbox', ['label' => 'published'])
            ->add('full_file', 'hidden')
            ->add('cover', 'hidden')
            ->add('header', 'hidden');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Entity\Issue',
                'tagEndPoint' => '/',
                'attr' => [
                    'novalidate' => 'novalidate',
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
