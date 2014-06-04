<?php

namespace Ojstr\JournalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleCitationType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $entity = $builder->getData();
        if ($entity) {
            $articleId = $entity->getArticles()[0]->getId();
        } else {
            return;
        }
        $builder
                ->add('source')
                ->add('type')
                ->add('orderNum')
                ->add('articles', 'hidden', array(
                    'data' => $articleId)
                )
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Ojstr\JournalBundle\Entity\Citation'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'ojstr_journalbundle_article_citation';
    }

}
