<?php

namespace Vipa\JournalBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Vipa\CoreBundle\Form\Type\JournalBasedTranslationsType;
use Vipa\CoreBundle\Params\PublisherStatuses;
use Vipa\JournalBundle\Entity\Journal;
use Vipa\JournalBundle\Entity\PublisherRepository;
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
            ->add('translations', JournalBasedTranslationsType::class, [
                'fields' => [
                    'title' => [
                        'label' => 'journal.title'
                    ],
                    'subtitle' => [
                        'required' => false,
                        'label' => 'journal.subtitle'
                    ],
                    'description' => [
                        'attr' => array('class' => ' form-control wysihtml5'),
                        'field_type' => 'purified_textarea',
                        'required' => false,
                    ],
                    'titleAbbr' => [
                        'required' => false
                    ],
                    'footerText' => [
                        'label' => 'footer_text',
                        'attr' => array('class' => ' form-control wysihtml5'),
                        'field_type' => 'purified_textarea',
                        'required' => false,
                    ],
                    'mailSignature' => [
                        'label' => 'journal.settings.mail.signature',
                        'field_type' => 'text',
                        'required' => false,
                    ]
                ]
            ])
            ->add('titleTransliterated', 'text', [
                    'label' => 'titleTransliterated',
                    'required' => false
                ]
            )
            ->add(
                'publisher',
                'entity',
                [
                    'required' => true,
                    'label' => 'publisher',
                    'attr' => [
                        'class' => 'select2-element',
                    ],
                    'placeholder' => 'select.publisher',
                    'class' => 'VipaJournalBundle:Publisher',
                    'query_builder' => function(PublisherRepository $er) {
                        return $er->createQueryBuilder('publisher')
                            ->andWhere('publisher.status = :status')
                            ->andWhere('publisher.verified = :verified')
                            ->setParameter('status', PublisherStatuses::STATUS_COMPLETE)
                            ->setParameter('verified', true)
                            ;
                    }
                ]
            )
            ->add(
                'mandatoryLang',
                'entity',
                [
                    'label' => 'journal.mandatory_lang',
                    'class' => 'Vipa\JournalBundle\Entity\Lang',
                    'attr' => [
                        'class' => 'select2-element ',
                    ]
                ]
            )
            ->add(
                'languages',
                'entity',
                array(
                    'label' => 'journal.supported_languages',
                    'class' => 'Vipa\JournalBundle\Entity\Lang',
                    'property' => 'name',
                    'multiple' => true,
                    'required' => false,
                    'expanded' => false,
                    'attr' => [
                        'class' => 'select2-element validate[required]',
                    ]
                )
            )
            ->add(
                'accessModal',
                'choice',
                [
                    'label' => 'journal.access.modal',
                    'choices' => [
                        0 => 'open.access',
                        1 => 'access.with.subscription',
                    ],
                ]
            )
            ->add(
                'periods',
                'entity',
                array(
                    'label' => 'journal.period',
                    'class' => 'Vipa\JournalBundle\Entity\Period',
                    'multiple' => true,
                    'expanded' => false,
                    'attr' => [
                        'class' => 'select2-element validate[required]',
                    ]
                )
            )
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
                        'startDate' => date('01/01/1991'),
                    ],
                )
            )
            ->add(
                'endingDate',
                'collot_datetime',
                array(
                    'required' => false,
                    'label' => 'journal.endingDate',
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
            ->add('domain', null, ['label' => 'journal.domain'])
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
                    'class' => 'BulutYazilim\LocationBundle\Entity\Country',
                    'attr' => [
                        'class' => 'select2-element ',
                    ],
                ]
            )
            ->add('printed', 'checkbox', [
                    'label' => 'printed',
                    'required' => false
                ]
            )
            ->add('slug', 'text', [
                    'label' => 'journal.slug',
                    'required' => false,
                ]
            )
            ->add('tags', 'tags', ['label' => 'tags'])
            ->add(
                'theme',
                'entity',
                array(
                    'label' => 'theme',
                    'class' => 'Vipa\JournalBundle\Entity\JournalTheme',
                    'property' => 'title',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'error_bubbling'=>true,
                )
            )
            ->add(
                'design',
                'entity',
                array(
                    'label' => 'design',
                    'class' => 'Vipa\JournalBundle\Entity\Design',
                    'property' => 'title',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'query_builder' => function (EntityRepository $er) use ($journalId, $options){
                        $query = $er->createQueryBuilder('t');
                        if($journalId !== null){
                            $query->where('t.owner = :journal')
                                ->setParameter('journal', $options['data']);
                        }
                        return $query;
                    },
                    'error_bubbling'=>true,
                )
            )
            ->add('header', 'jb_crop_image_ajax', array(
                'endpoint' => 'journal',
                'label' => 'journal.header_image',
                'img_width' => 960,
                'img_height' => 200,
                'required' => false,
                'crop_options' => array(
                    'aspect-ratio' => 960 / 200,
                    'maxSize' => "[960, 200]"
                )
            ))
            ->add('image', 'jb_crop_image_ajax', array(
                'endpoint' => 'journal',
                'label' => 'cover_image',
                'img_width' => 200,
                'img_height' => 300,
                'required' => false,
                'crop_options' => array(
                    'aspect-ratio' => 200 / 300,
                    'maxSize' => "[200, 300]"
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
                'data_class' => Journal::class,
                'cascade_validation' => true
            )
        );
    }
}
