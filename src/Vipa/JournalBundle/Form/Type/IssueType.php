<?php

namespace Vipa\JournalBundle\Form\Type;

use Vipa\CoreBundle\Form\Type\JournalBasedTranslationsType;
use Vipa\CoreBundle\Params\IssueDisplayModes;
use Vipa\CoreBundle\Params\IssueVisibilityStatuses;
use Vipa\JournalBundle\Entity\Issue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

class IssueType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', JournalBasedTranslationsType::class, [
                    'fields' => [
                        'title'       => ['required' => false],
                        'description' => [
                            'required' => false,
                        ],
                    ],
                ]
            )
            ->add('volume', 'text', ['label' => 'volume', 'required' => false])
            ->add('number', 'text', ['label' => 'number', 'required' => false])
            ->add(
                'display_mode',
                'choice',
                [
                    'choices'           => [
                        'display_mode.volume_and_number' => IssueDisplayModes::SHOW_VOLUME_AND_NUMBER,
                        'display_mode.title'             => IssueDisplayModes::SHOW_TITLE,
                        'display_mode.all'               => IssueDisplayModes::SHOW_ALL,
                    ],
                    'data'              => IssueDisplayModes::SHOW_VOLUME_AND_NUMBER,
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
                        'issue.visibility.early_pub'     => IssueVisibilityStatuses::EARLY_PUB,
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
            ->add(
                'year',
                'collot_datetime',
                array(
                    'label' => 'year',
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
        $builder->addEventListener(FormEvents::PRE_SUBMIT, self::preSubmitCallback());
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
                } else if ($issue->getEarlyPub() && $issue->isPublished()) {
                    $visibility = IssueVisibilityStatuses::EARLY_PUB;
                } else if ($issue->isPublished()) {
                    $visibility = IssueVisibilityStatuses::PUBLISHED;
                }

                $form->get('visibility')->setData($visibility);
            }
        };
    }

    public static function preSubmitCallback()
    {
        return function (FormEvent $event) {
            $data = $event->getData();
            $isTitleEmpty = false;

            foreach ($data['translations'] as $translation) {
                $isTitleEmpty = $isTitleEmpty || $translation['title'] === '';
            }

            $displayMode = PropertyAccess::createPropertyAccessor()->getValue($data, '[display_mode]');
            $isTitleDisplayed = $displayMode == IssueDisplayModes::SHOW_ALL || $displayMode == IssueDisplayModes::SHOW_TITLE;

            if ($isTitleEmpty && !$isTitleDisplayed) {
                foreach ($data['translations'] as &$translation) {
                    $translation['title'] = '-';
                }
            }

            $event->setData($data);
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
                $issue->setEarlyPub($visibility == IssueVisibilityStatuses::EARLY_PUB);
                $issue->setPublished(
                    $visibility == IssueVisibilityStatuses::IN_PRESS ||
                    $visibility == IssueVisibilityStatuses::PUBLISHED||
                    $visibility == IssueVisibilityStatuses::EARLY_PUB
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
                'data_class'         => Issue::class,
                'cascade_validation' => true,
                'novalidate'         => 'novalidate',
                'attr'               => [
                    'class' => 'form-validate',
                ],
            ]
        );
    }
}
