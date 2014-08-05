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
                ->add('issueId', 'integer', array('required' => false, 'attr' => array('class' => ' form-control')))
                ->add('status', 'integer', array('attr' => array('class' => ' form-control')))
                ->add('doi', 'text', array('required' => false, 'attr' => array('class' => ' form-control')))
                ->add('otherId', 'text', array('required' => false, 'attr' => array('class' => ' form-control')))
                ->add('keywords', 'text', array('attr' => array('class' => ' form-control')))
                ->add('journalId', 'integer', array('attr' => array('class' => ' form-control')))
                ->add('title', 'text', array('attr' => array('class' => ' form-control')))
                ->add('titleTransliterated', 'text', array('required' => false, 'attr' => array('class' => ' form-control')))
                ->add('subtitle', 'text', array('required' => false, 'attr' => array('class' => ' form-control')))
                ->add('isAnonymous', 'radio', array('required' => false))
                ->add('orderNum', 'integer', array('required' => false))
                ->add('pubdate', 'date', array(
                    'required' => false,
                    'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
                    'attr' => array('class' => 'dateselector form-control')
                ))
                ->add('pubdateSeason', 'text', array('required' => false, 'attr' => array('class' => ' form-control')))
                ->add('part', 'text', array('required' => false, 'attr' => array('class' => ' form-control')))
                ->add('firstPage', 'integer', array('required' => false, 'attr' => array('class' => ' form-control')))
                ->add('lastPage', 'integer', array('required' => false, 'attr' => array('class' => ' form-control')))
                ->add('uri', 'text', array('required' => false, 'attr' => array('class' => ' form-control')))
                ->add('abstract', 'textarea', array('required' => false, 'attr' => array('class' => ' form-control')))
                ->add('abstractTransliterated', 'textarea', array('required' => false, 'attr' => array('class' => ' form-control')));
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
