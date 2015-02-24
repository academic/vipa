<?php

namespace Ojs\JournalBundle\Form;

use Ojs\Common\Params\CommonParams;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JournalType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('title')
                ->add('titleAbbr')
                ->add('titleTransliterated')
                ->add('languages', 'entity', array(
                    'class' => 'Ojs\JournalBundle\Entity\Lang',
                    'property' => 'name',
                    'multiple' => true,
                    'expanded' => false,
                    'required' => false
                        )
                )
                ->add('subtitle')
                ->add('subdomain')
                ->add('domain')
                ->add('issn')
                ->add('eissn')
                ->add('firstPublishDate', 'datetime', array(
                    'date_widget' => 'single_text',
                    'time_widget' => 'single_text',
                    'date_format' => 'yyyy-MM-dd',
                    'with_seconds' => true,
                    'data' => new \DateTime()
                ))
                ->add('period')
                ->add('url')
                ->add('country','entity',[
                    'class'=>'Okulbilisim\LocationBundle\Entity\Country',
                    'attr'=>[
                        'class'=>'select2'
                    ]
                ])
                ->add('published')
                ->add('status','choice',[
                    'choices'=>CommonParams::getStatusTexts()
                ])
                ->add('image', 'hidden')
                ->add('header', 'hidden')
                ->add('scope')
                ->add('mission')
                ->add('slug')
                ->add('theme', 'entity', array(
                    'class' => 'Ojs\JournalBundle\Entity\Theme',
                    'property' => 'title',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                        return $er->createQueryBuilder('t')
                                ->where('t.isPublic IS NULL OR t.isPublic = TRUE');
                    })
                )
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\Journal'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'ojs_journalbundle_journal';
    }

}
