<?php

namespace Ojs\JournalBundle\Form\Type;

use Ojs\CoreBundle\Params\IssueDisplayModes;
use Ojs\CoreBundle\Params\IssueVisibilityStatuses;
use Ojs\JournalBundle\Entity\Issue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'translations',
                'a2lix_translations',
                [
                    'fields' => [
                        'title'       => [],
                        'description' => [
                            'required' => false,
                        ],
                    ],
                ]
            )
            ->add('volume', 'integer', ['label' => 'volume', 'required' => false])
            ->add('number', 'integer', ['label' => 'number', 'required' => false])
            ->add(
                'display_mode',
                'choice',
                [
                    'choices'           => [
                        'display_mode.all'               => IssueDisplayModes::SHOW_ALL,
                        'display_mode.title'             => IssueDisplayModes::SHOW_TITLE,
                        'display_mode.volume_and_number' => IssueDisplayModes::SHOW_VOLUME_AND_NUMBER,
                    ],
                    'choices_as_values' => true,
                    'label'             => 'display_mode',
                    'required'          => false,
                ]
            )
            ->add(
                'visibility',
                'choice',
                [
                    'choices'           => [
                        'issue.visibility.not_published' => IssueVisibilityStatuses::NOT_PUBLISHED,
                        'issue.visibility.published'     => IssueVisibilityStatuses::PUBLISHED,
                        'issue.visibility.in_press'      => IssueVisibilityStatuses::IN_PRESS,
                    ],
                    'choices_as_values' => true,
                    'label'             => 'published',
                    'required'          => true,
                    'mapped'            => false,
                ]
            )
            ->add(
                'special',
                'checkbox',
                [
                    'label'    => 'special.issue',
                    'required' => false,
                ]
            )
            ->add(
                'supplement',
                'checkbox',
                [
                    'label'    => 'issue.supplement',
                    'required' => false,
                ]
            )
            ->add('year', 'text', ['label' => 'year'])
            ->add(
                'datePublished',
                'collot_datetime',
                [
                    'label'         => 'issue.publishdate',
                    'date_format'   => 'dd-MM-yyyy',
                    'pickerOptions' => [
                        'format'         => 'dd-mm-yyyy',
                        'startView'      => 'month',
                        'minView'        => 'month',
                        'todayBtn'       => 'true',
                        'todayHighlight' => 'true',
                        'autoclose'      => 'true',
                    ],
                ]
            )
            ->add('tags', 'tags', ['label' => 'tags'])
            ->add(
                'full_file',
                'jb_file_ajax',
                [
                    'endpoint' => 'issuefiles',
                    'label'    => 'issue.full_file',
                    'required' => false,
                ]
            )
            ->add(
                'cover',
                'jb_crop_image_ajax',
                [
                    'endpoint'     => 'journal',
                    'img_width'    => 200,
                    'img_height'   => 300,
                    'crop_options' => [
                        'aspect-ratio' => 200 / 300,
                        'maxSize'      => "[200, 300]",
                    ],
                    'label'        => 'issue.cover',
                    'required'     => false,
                ]
            )
            ->add(
                'header',
                'jb_crop_image_ajax',
                [
                    'endpoint'     => 'journal',
                    'img_width'    => 960,
                    'img_height'   => 200,
                    'crop_options' => [
                        'aspect-ratio' => 960 / 200,
                        'maxSize'      => "[960, 200]",
                    ],
                    'label'        => 'issue.header',
                    'required'     => false,
                ]
            );

        $builder->addEventListener(FormEvents::POST_SET_DATA, self::postSetDataCallback());
        $builder->addEventListener(FormEvents::POST_SUBMIT, self::postSubmitCallback());
    }

    /**
     * @return \Closure
     */
    public static function postSetDataCallback()
    {
        return function (FormEvent $event) {
            /** @var Issue $issue */
            $issue = $event->getData();
            $form = $event->getForm();

            if ($issue instanceof Issue) {
                $visibility = IssueVisibilityStatuses::NOT_PUBLISHED;

                if ($issue->getInPress() && $issue->isPublished()) {
                    $visibility = IssueVisibilityStatuses::IN_PRESS;
                } else if ($issue->isPublished()) {
                    $visibility = IssueVisibilityStatuses::PUBLISHED;
                }

                $form->get('visibility')->setData($visibility);
            }
        };
    }

    /**
     * @return \Closure
     */
    public static function postSubmitCallback()
    {
        return function (FormEvent $event) {
            /** @var Issue $issue */
            $issue = $event->getData();
            $form = $event->getForm();

            if ($issue instanceof Issue) {
                $visibility = $form->get('visibility')->getData();

                $issue->setInPress($visibility == IssueVisibilityStatuses::IN_PRESS);
                $issue->setPublished(
                    $visibility == IssueVisibilityStatuses::IN_PRESS ||
                    $visibility == IssueVisibilityStatuses::PUBLISHED
                );

                $event->setData($issue);
            }
        };
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'         => 'Ojs\JournalBundle\Entity\Issue',
                'cascade_validation' => true,
                'novalidate'         => 'novalidate',
                'attr'               => [
                    'class' => 'form-validate',
                ],
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_issue';
    }
}
