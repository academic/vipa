<?php

namespace Ojs\JournalBundle\Form;

use Ojs\Common\Params\CommonParams;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JournalType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('title','text',[
                    'attr'=>[
                        'class'=>'validate[required]'
                    ]
                ])
                ->add('titleAbbr')
                ->add('titleTransliterated')
                ->add('institution', null, [
                    'attr' => [
                        'class' => 'select2-element validate[required]'
                    ]
                ])
                ->add('languages', 'entity', array(
                    'class' => 'Ojs\JournalBundle\Entity\Lang',
                    'property' => 'name',
                    'multiple' => true,
                    'expanded' => false,
                    'required' => false,
                    'attr' => [
                        'class' => 'select2-element validate[required]',
                    ]
                        )
                )
                ->add('subjects', 'entity', [
                    'class' => 'Ojs\JournalBundle\Entity\Subject',
                    'property' => 'subject',
                    'multiple' => true,
                    'attr' => [
                        'class' => 'select2-element'
                    ]
                ])
                ->add('submitRoles', 'entity', array(
                    'class' => 'Ojs\UserBundle\Entity\Role',
                    'property' => 'name',
                    'multiple' => true,
                    'expanded' => false,
                    'required' => false,
                    'attr' => [
                        'class' => 'select2-element'
                    ]
                        )
                )
                ->add('subtitle')
                ->add('path')
                ->add('domain')
                ->add('issn', 'text', array('label' => 'ISSN', 'attr' => array('class' => 'maskissn')))
                ->add('eissn', 'text', array('label' => 'e-ISSN', 'attr' => array('class' => 'maskissn')))
                ->add('firstPublishDate', 'datetime', array(
                    'date_widget' => 'single_text',
                    'time_widget' => 'single_text',
                    'date_format' => 'yyyy-MM-dd',
                    'with_seconds' => true,
                    'data' => new \DateTime()
                ))
                ->add('period')
                ->add('url')
                ->add('country', 'entity', [
                    'class' => 'Okulbilisim\LocationBundle\Entity\Country',
                    'attr' => [
                        'class' => 'select2-element '
                    ]
                ])
                ->add('footer_text', 'textarea', [
                    'attr' => [
                        'class' => 'wysihtml5 '
                    ]
                ])
                ->add('published','checkbox')
                ->add('status', 'choice', [
                    'choices' => CommonParams::getStatusTexts()
                ])
                ->add('image', 'hidden')
                ->add('header', 'hidden')
                ->add('logo', 'hidden')
                ->add('slug')
                ->add('tags', 'text', ['attr' => ['class' => 'select2-tags', 'data-role' => '']])
                ->add('description', 'textarea',['attr'=>['class'=>'validate[required]']])
                ->add('theme', 'entity', array(
                    'class' => 'Ojs\JournalBundle\Entity\Theme',
                    'property' => 'title',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'query_builder' => function (\Doctrine\ORM\EntityRepository $er) {
                        return $er->createQueryBuilder('t')
                                ->where('t.isPublic IS NULL OR t.isPublic = TRUE');
                    })
        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\Journal',
            'attr'=>[
                'novalidate'=>'novalidate',
                'class'=>'validate-form'
            ]
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_journal';
    }

}
