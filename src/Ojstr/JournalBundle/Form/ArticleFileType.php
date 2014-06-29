<?php

namespace Ojstr\JournalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleFileType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
            $builder
//                ->add('path')
//                ->add('name')
//                ->add('mimeType')
//                ->add('size')
                  ->add('articleId');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Ojstr\JournalBundle\Entity\ArticleFile'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'ojstr_journalbundle_articlefile';
    }

}
