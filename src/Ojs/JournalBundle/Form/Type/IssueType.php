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
            ->add('translations', 'a2lix_translations_gedmo',[
                'translatable_class' => 'Ojs\JournalBundle\Entity\Issue'
            ])
            ->add('volume', 'text', array('label' => 'volume'))
            ->add('number', 'text', array('label' => 'number'))
            ->add('special', 'checkbox', array('label' => 'special'))
            ->add('supplement', 'checkbox', array('label' => 'supplement'))
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
            ->add('full_file', 'hidden')
            ->add('cover', 'hidden')
            ->add('header', 'hidden');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Entity\Issue',
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
