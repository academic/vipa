<?php

namespace Ojs\JournalBundle\Form;

use Doctrine\ORM\EntityRepository;
use Ojs\Common\Params\CommonParams;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JournalType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', [
                'label' => 'title',
                'attr' => [
                    'class' => 'validate[required]',
                ],
            ])
            ->add('titleAbbr', 'text', ['label' => 'titleabbr'])
            ->add('titleTransliterated', 'text', ['label' => 'titleTransliterated'])
            ->add('institution', null, [
                'label' => 'institution',
                'attr' => [
                    'class' => 'select2-element validate[required]',
                ],
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
                    ],
                )
            )
            ->add('subjects', 'entity', [
                'label' => 'subjects',
                'class' => 'Ojs\JournalBundle\Entity\Subject',
                'property' => 'subject',
                'multiple' => true,
                'attr' => [
                    'class' => 'select2-element',
                ],
            ]);
        $role_options = [
            'label' => 'submitRoles',
            'class' => 'Ojs\UserBundle\Entity\Role',
            'property' => 'name',
            'multiple' => true,
            'expanded' => false,
            'required' => false,
            'attr' => [
                'class' => 'select2-element',
            ],
        ];
        if ($options['default_roles'] !== null) {
            $role_options['data'] = $options['default_roles'];
        }

        $builder->add('submitRoles', 'entity', $role_options);

        $builder->add('subtitle', 'hidden', ['label' => 'subtitle'])
            ->add('path', 'hidden', ['label' => 'journal.path'])
            ->add('domain', 'hidden', ['label' => 'journal.domain'])
            ->add('issn', 'text', array('label' => 'ISSN', 'attr' => array('class' => 'maskissn')))
            ->add('eissn', 'text', array('label' => 'eISSN', 'attr' => array('class' => 'maskissn')))
            ->add('firstPublishDate', 'collot_datetime', array(
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
            ))
            ->add('period', 'text', ['label' => 'journal.period'])
            ->add('googleAnalyticsId', 'text', [
                'label' => 'journal.google.analytics.id',
            ])
            ->add('url', 'text', ['label' => 'url'])
            ->add('country', 'entity', [
                'label' => 'country',
                'class' => 'Okulbilisim\LocationBundle\Entity\Location',
                'attr' => [
                    'class' => 'select2-element ',
                ],
                'query_builder' => function (EntityRepository $em) {
                    return $em->createQueryBuilder('c')
                        ->where("c.type=0");
                },
            ])
            ->add('footer_text', 'textarea', [
                'label' => 'footer_text',
                'attr' => [
                    'class' => 'wysihtml5 ',
                ],
            ])
            ->add('published', 'checkbox', ['label' => 'published'])
            ->add('status', 'choice', [
                'label' => 'status',
                'choices' => CommonParams::getStatusTexts(),
            ])
            ->add('image', 'hidden')
            ->add('header', 'hidden')
            ->add('logo', 'hidden')
            ->add('slug', 'text', ['label' => 'journal.slug'])
            ->add('tags', 'text', ['label' => 'tags', 'attr' => ['class' => 'select2-tags', 'data-role' => '']])
            ->add('description', 'textarea', ['label' => 'description', 'attr' => ['class' => 'validate[required]']])
            ->add('theme', 'entity', array(
                    'label' => 'theme',
                    'class' => 'Ojs\JournalBundle\Entity\Theme',
                    'property' => 'title',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('t')
                            ->where('t.isPublic IS NULL OR t.isPublic = TRUE');
                    }, )
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
                'class' => 'validate-form',
            ],
            'default_roles' => null,
            'translation_domain' => 'messages',

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
