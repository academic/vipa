<?php

namespace Ojs\AdminBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Ojs\Common\Params\CommonParams;
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
        $builder
            ->add('translations', 'a2lix_translations_gedmo',[
                'translatable_class' => 'Ojs\JournalBundle\Entity\Journal',
                'fields' => [
                    'title' => [
                        'label' => 'title'
                    ],
                    'subtitle' => [
                        'locale_options' => [
                            'tr' => [
                                'display' => false
                            ],
                            'en' => [
                                'display' => false
                            ],
                            'fr' => [
                                'display' => false
                            ]

                        ]
                    ]
                ],
                'error_bubbling'=>true
            ])
            ->add('titleAbbr', 'text', ['label' => 'titleabbr',
                'error_bubbling'=>true])
            ->add('titleTransliterated', 'text', ['label' => 'titleTransliterated',
                'error_bubbling'=>true])
            ->add(
                'institution',
                null,
                [
                    'label' => 'institution',
                    'attr' => [
                        'class' => 'select2-element validate[required]',
                    ],
                    'error_bubbling'=>true
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
                    ],
                    'error_bubbling'=>true,
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
                    'required' => false,
                    'attr' => [
                        'class' => 'select2-element validate[required]',
                    ],
                    'error_bubbling'=>true
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
                    ],
                    'error_bubbling'=>true,
                ]
            );

        $builder->add('subtitle', 'hidden', ['label' => 'subtitle'])
            ->add('path', 'hidden', ['label' => 'journal.path',
                'error_bubbling'=>true])
            ->add('domain', 'hidden', ['label' => 'journal.domain',
                'error_bubbling'=>true])
            ->add('issn', 'text', array('label' => 'ISSN', 'attr' => array('class' => 'maskissn'),
                'error_bubbling'=>true))
            ->add('eissn', 'text', array('label' => 'eISSN', 'attr' => array('class' => 'maskissn'),
                'error_bubbling'=>true))
            ->add(
                'firstPublishDate',
                'collot_datetime',
                array(
                    'label' => 'journal.firstPublishDate',
                    'date_format' => 'dd-MM-yyyy',
                    'pickerOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'startView' => 'month',
                        'minView' => 'month',
                        'todayBtn' => 'true',
                        'todayHighlight' => 'true',
                        'autoclose' => 'true',
                    ],
                    'error_bubbling'=>true,
                )
            )
            ->add('domain')
            ->add('period', 'text', ['label' => 'journal.period',
                'error_bubbling'=>true])
            ->add(
                'googleAnalyticsId',
                'text',
                [
                    'label' => 'journal.google.analytics.id',
                    'error_bubbling'=>true,
                ]
            )
            ->add('url', 'text', ['label' => 'url'])
            ->add(
                'country',
                'entity',
                [
                    'label' => 'country',
                    'class' => 'Ojs\LocationBundle\Entity\Country',
                    'attr' => [
                        'class' => 'select2-element ',
                    ],
                    'error_bubbling'=>true,
                ]
            )
            ->add(
                'footer_text',
                'textarea',
                [
                    'label' => 'footer_text',
                    'attr' => [
                        'class' => 'wysihtml5 ',
                    ],
                    'error_bubbling'=>true,
                ]
            )
            ->add('published', 'checkbox', ['label' => 'published',
                'error_bubbling'=>true])
            ->add('printed', 'checkbox', ['label' => 'printed',
                'error_bubbling'=>true])
            ->add(
                'status',
                'choice',
                [
                    'label' => 'status',
                    'choices' => CommonParams::getStatusTexts(),
                    'error_bubbling'=>true,
                ]
            )
            ->add('slug', 'text', ['label' => 'journal.slug',
                'error_bubbling'=>true])
            ->add('tags', 'tags')
            ->add('description', 'textarea', ['label' => 'description',
                'error_bubbling'=>true, 'attr' => ['class' => 'validate[required]']])
            ->add(
                'theme',
                'entity',
                array(
                    'label' => 'theme',
                    'class' => 'Ojs\JournalBundle\Entity\Theme',
                    'property' => 'title',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('t')
                            ->where('t.isPublic IS NULL OR t.isPublic = TRUE');
                    },
                    'error_bubbling'=>true,
                )
            )
            ->add(
                'design',
                'entity',
                array(
                    'label' => 'design',
                    'class' => 'Ojs\JournalBundle\Entity\Design',
                    'property' => 'title',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('t')
                            ->where('t.isPublic IS NULL OR t.isPublic = TRUE');
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
            ->add('competingFile', 'jb_file_ajax',
                    array(
                        'endpoint' => 'journalCompeting'
                    )
                )
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
                'attr' => [
                    'novalidate' => 'novalidate',
                    'class' => 'validate-form',
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
