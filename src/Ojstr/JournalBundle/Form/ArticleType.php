<?php

namespace Ojstr\JournalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('status','integer', array('attr' => array('class' => ' form-control')))
                ->add('doi','text', array('attr' => array('class' => ' form-control')))
                ->add('otherId','text', array('attr' => array('class' => ' form-control')))
                ->add('keywords','text', array('attr' => array('class' => ' form-control')))
                ->add('subjects', 'entity', array(
                    'class' => 'Ojstr\JournalBundle\Entity\Subject',
                    'property' => 'subject',
                    'multiple' => true,
                    'expanded' => false,
                    'required' => false
                        )
                )
                ->add('journalId','integer', array('attr' => array('class' => ' form-control')))
                ->add('title','text', array('attr' => array('class' => ' form-control')))
                ->add('titleTransliterated','text', array('attr' => array('class' => ' form-control')))
                ->add('subtitle','text', array('attr' => array('class' => ' form-control')))
                ->add('isAnonymous')
                ->add('pubdate', 'date', array(
                    'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
                    'attr' => array('class' => 'dateselector form-control')
                ))
                ->add('pubdateSeason', 'text', array('attr' => array('class' => ' form-control')))
                ->add('part', 'text', array('attr' => array('class' => ' form-control')))
                ->add('firstPage', 'integer', array('attr' => array('class' => ' form-control')))
                ->add('lastPage', 'integer', array('attr' => array('class' => ' form-control')))
                ->add('uri', 'text', array('attr' => array('class' => ' form-control')))
                ->add('abstract', 'textarea', array('attr' => array('class' => ' form-control')))
                ->add('abstractTransliterated', 'textarea', array('attr' => array('class' => ' form-control')));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Ojstr\JournalBundle\Entity\Article'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'ojstr_journalbundle_article';
    }

}
