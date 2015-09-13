<?php

namespace Ojs\AdminBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournalType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $journalId = $options['data']->getId()?$options['data']->getId(): null;
        $builder
            ->add('translations', 'a2lix_translations')
            ->add('titleAbbr', 'text', [
                'label' => 'titleabbr',
                'required' => false
                ]
            )
            ->add('titleTransliterated', 'text', [
                'label' => 'titleTransliterated',
                'required' => false
                ]
            )
            ->add(
                'publisher',
                null,
                [
                    'label' => 'publisher',
                    'attr' => [
                        'class' => 'select2-element validate[required]',
                    ]
                ]
            )
            ->add(
                'mandatoryLang',
                'entity',
                [
                    'label' => 'Mandatory Lang',
                    'class' => 'Ojs\JournalBundle\Entity\Lang',
                    'attr' => [
                        'class' => 'select2-element ',
                    ]
                ]
            )
            ->add(
                'languages',
                'entity',
                array(
                    'label' => 'Supported Languages',
                    'class' => 'Ojs\JournalBundle\Entity\Lang',
                    'property' => 'name',
                    'multiple' => true,
                    'expanded' => false,
                    'attr' => [
                        'class' => 'select2-element validate[required]',
                    ]
                )
            )
            ->add(
                'periods',
                'entity',
                array(
                    'label' => 'Periods',
                    'class' => 'Ojs\JournalBundle\Entity\Period',
                    'property' => 'period',
                    'multiple' => true,
                    'expanded' => false,
                    'attr' => [
                        'class' => 'select2-element validate[required]',
                    ]
                )
            )
            ->add(
                'subjects',
                'entity',
                [
                    'label' => 'subjects',
                    'class' => 'Ojs\JournalBundle\Entity\Subject',
                    'property' => 'subject',
                    'multiple' => true,
                    'attr' => [
                        'class' => 'select2-element',
                    ]
                ]
            );

        $builder
            ->add('path', 'hidden', [
                'label' => 'journal.path',
                'required' => false
                ]
            )
            ->add('domain', 'hidden', [
                'label' => 'journal.domain',
                'required' => false
                ]
            )
            ->add('issn', 'text', [
                'label' => 'ISSN',
                'required' => false,
                'attr' => [
                    'class' => 'maskissn',
                ]
                ]
            )
            ->add('eissn', 'text', array(
                'label' => 'eISSN',
                'required' => false,
                'attr' => array(
                    'class' => 'maskissn'
                )
                )
            )
            ->add(
                'founded',
                'collot_datetime',
                array(
                    'label' => 'journal.founded',
                    'date_format' => 'yyyy',
                    'widget' => 'single_text',
                    'pickerOptions' => [
                        'format' => 'yyyy',
                        'startView' => 'decade',
                        'minView' => 'decade',
                        'todayBtn' => 'true',
                        'todayHighlight' => 'true',
                        'autoclose' => 'true',
                    ],
                )
            )
            ->add('domain')
            ->add(
                'googleAnalyticsId',
                'text',
                [
                    'label' => 'journal.google.analytics.id',
                    'required' => false,
                ]
            )
            ->add('url', 'text', [
                'label' => 'url',
                'required' => false,
                ]
            )
            ->add(
                'country',
                'entity',
                [
                    'label' => 'country',
                    'class' => 'OkulBilisim\LocationBundle\Entity\Country',
                    'attr' => [
                        'class' => 'select2-element ',
                    ],
                ]
            )
            ->add(
                'footer_text',
                'textarea',
                [
                    'label' => 'footer_text',
                    'required' => false,
                    'attr' => [
                        'class' => 'wysihtml5',
                    ],
                ]
            )
            ->add('published', 'checkbox', [
                'label' => 'published',
                'required' => false
                ]
            )
            ->add('printed', 'checkbox', [
                'label' => 'printed',
                'required' => false
                ]
            )
            ->add(
                'status',
                'choice',
                [
                    'label' => 'status',
                    'choices' => Journal::$statuses,
                ]
            )
            ->add('slug', 'text', [
                'label' => 'journal.slug',
                'required' => false,
                ]
            )
            ->add('tags', 'tags')
            ->add(
                'theme',
                'entity',
                array(
                    'label' => 'theme',
                    'class' => 'Ojs\JournalBundle\Entity\JournalTheme',
                    'property' => 'title',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'query_builder' => function (EntityRepository $er) use ($journalId, $options) {
                        $query = $er->createQueryBuilder('t');
                        if(is_null($journalId)){
                            $query->where('t.public IS NULL OR t.public = TRUE');
                        }else{
                            $query->where('t.public IS NULL OR t.public = TRUE OR t.journal = :journal')
                            ->setParameter('journal', $options['data']);
                        }
                        return $query;
                    },
                    'error_bubbling'=>true,
                )
            )
            ->add(
                'design',
                'entity',
                array(
                    'label' => 'design',
                    'class' => 'Ojs\JournalBundle\Entity\JournalDesign',
                    'property' => 'title',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'query_builder' => function (EntityRepository $er) use ($journalId, $options){
                        $query = $er->createQueryBuilder('t');
                        if(is_null($journalId)){
                            $query->where('t.public IS NULL OR t.public = TRUE');
                        }else{
                            $query->where('t.public IS NULL OR t.public = TRUE OR t.journal = :journal')
                                ->setParameter('journal', $options['data']);
                        }
                        return $query;
                    },
                    'error_bubbling'=>true,
                )
            )
            ->add('header', 'jb_crop_image_ajax', array(
                'endpoint' => 'journal',
                'label' => 'Header Image',
                'img_width' => 960,
                'img_height' => 200,
                'crop_options' => array(
                    'aspect-ratio' => 960 / 200,
                    'maxSize' => "[960, 200]"
                )
            ))
            ->add('image', 'jb_crop_image_ajax', array(
                'endpoint' => 'journal',
                'label' => 'Cover Image',
                'img_width' => 200,
                'img_height' => 300,
                'crop_options' => array(
                    'aspect-ratio' => 200 / 300,
                    'maxSize' => "[200, 300]"
                )
            ))
            ->add('logo', 'jb_crop_image_ajax', array(
                'endpoint' => 'journal',
                'img_width' => 200,
                'img_height' => 200,
                'crop_options' => array(
                    'aspect-ratio' => 200 / 200,
                    'maxSize' => "[200, 200]"
                )
            ))
            ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Entity\Journal',
                'cascade_validation' => true,
                'attr' => [
                ],
                'translation_domain' => 'messages',
                'csrf_protection'=>false
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_journal';
    }
}
