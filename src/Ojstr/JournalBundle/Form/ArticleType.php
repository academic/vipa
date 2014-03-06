<?php

namespace Ojstr\JournalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('doi')
            ->add('otherId')
            ->add('journalId')
            ->add('title')
            ->add('titleTranslated')
            ->add('subtitle')
            ->add('isAnonymous')
            ->add('pubdate')
            ->add('pubdateSeason')
            ->add('volume')
            ->add('issue')
            ->add('part')
            ->add('firstPage')
            ->add('lastPage')
            ->add('uri')
            ->add('abstract')
            ->add('abstractTranslated')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojstr\JournalBundle\Entity\Article'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojstr_journalbundle_article';
    }
}
