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
                ->add('status')
                ->add('doi')
                ->add('otherId')
                ->add('keywords')
                ->add('subjects', 'entity', array(
                    'class' => 'Ojstr\JournalBundle\Entity\Subject',
                    'property' => 'subject',
                    'multiple' => true,
                    'expanded' => false,
                    'required' => false
                        )
                )
                ->add('journalId')
                ->add('title')
                ->add('titleTransliterated')
                ->add('subtitle')
                ->add('isAnonymous')
                ->add('pubdate')
                ->add('pubdateSeason')
                ->add('part')
                ->add('firstPage')
                ->add('lastPage')
                ->add('uri')
                ->add('abstract')
                ->add('abstractTransliterated');
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
