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
                ->add('title', 'text', [
                    'label' => 'title',
                    'attr' => [
                        'class' => 'validate[required]'
                    ]
                ])
                ->add('titleAbbr', 'text',['label' => 'titleabbr'])
                ->add('titleTransliterated','text', ['label' => 'titleTransliterated'])
                ->add('institution', null, [
                    'label' => 'institution',
                    'attr' => [
                        'class' => 'select2-element validate[required]'
                    ]
                ])
                ->add('languages', 'entity', array(
                    'label' => 'languages',
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
                    'label' => 'subjects',
                    'class' => 'Ojs\JournalBundle\Entity\Subject',
                    'property' => 'subject',
                    'multiple' => true,
                    'attr' => [
                        'class' => 'select2-element'
                    ]
                ])
                ->add('submitRoles', 'entity', array(
                    'label' => 'submitRoles',
                    'class' => 'Ojs\UserBundle\Entity\Role',
                    'property' => 'name',
                    'multiple' => true,
                    'expanded' => false,
                    'required' => false,
                    'attr' => [
                        'class' => 'select2-element'
                    ],
                    'data' => $options['default_roles']
                        )
                )
                ->add('subtitle', 'text', [ 'label' => 'subtitle'])
                ->add('path', 'text', [ 'label' => 'journal.path'])
                ->add('domain', 'text', [ 'label' => 'journal.domain'])
                ->add('issn', 'text', array('label' => 'ISSN', 'attr' => array('class' => 'maskissn')))
                ->add('eissn', 'text', array('label' => 'eISSN', 'attr' => array('class' => 'maskissn')))
                ->add('firstPublishDate', 'collot_datetime', array(
                    'label' => 'journal.firstPublishDate',
                    'date_format' => 'yyyy-MM-dd',
                ))
                ->add('period', 'text', ['label' => 'journal.period'])
                ->add('url', 'text', ['label' => 'url'])
                ->add('country', 'entity', [
                    'label'=>'country',
                    'class' => 'Okulbilisim\LocationBundle\Entity\Country',
                    'attr' => [
                        'class' => 'select2-element '
                    ]
                ])
                ->add('footer_text', 'textarea', [
                    'label' => 'footer_text',
                    'attr' => [
                        'class' => 'wysihtml5 '
                    ]
                ])
                ->add('published', 'checkbox', ['label' => 'published'])
                ->add('status', 'choice', [
                    'label' => 'status',
                    'choices' => CommonParams::getStatusTexts()
                ])
                ->add('image', 'hidden')
                ->add('header', 'hidden')
                ->add('logo', 'hidden')
                ->add('slug','text',['label'=>'slug'])
                ->add('tags', 'text', ['attr' => ['class' => 'select2-tags', 'data-role' => '']])
                ->add('description', 'textarea', ['label'=> 'description', 'attr' => ['class' => 'validate[required]']])
                ->add('theme', 'entity', array(
                    'label'=> 'theme',
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
            'attr' => [
                'novalidate' => 'novalidate',
                'class' => 'validate-form'
            ],
            'default_roles' => null
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
