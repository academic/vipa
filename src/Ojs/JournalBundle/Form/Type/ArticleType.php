<?php

namespace Ojs\JournalBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Ojs\Common\Params\CommonParams;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Journal $journal */
        $journal = $options['journal'];
        $builder
            ->add('translations', 'a2lix_translations_gedmo',[
                'translatable_class' => 'Ojs\JournalBundle\Entity\Article',
                'fields' => [
                    'title' => [
                        'field_type' => 'text'
                    ],
                    'titleTransliterated' => [],
                    'subtitle' => [],
                    'subjects' => [],
                    'keywords' => [
                        'label' => 'keywords',
                        'field_type' => 'tags'
                    ],
                    'abstract' => [
                        'required' => false,
                        'attr' => array('class' => ' form-control wysihtml5'),
                        'field_type' => 'textarea'
                    ]
                ]
            ])
            ->add(
                'issue',
                'entity',
                array(
                    'label' => 'issue',
                    'class' => 'Ojs\JournalBundle\Entity\Issue',
                    'required' => false,
                    'attr' => array('class' => ' form-control select2-element'),
                    'query_builder' => function (EntityRepository $er) use ($journal) {
                        $qb = $er->createQueryBuilder('i');
                        $qb->where('i.journal = :journal')
                            ->setParameter('journal', $journal);

                        return $qb;
                    },
                )
            )
            ->add(
                'status',
                'choice',
                array(
                    'label' => 'status',
                    'attr' => array('class' => ' form-control'),
                    'choices' => CommonParams::statusText(),
                )
            )
            ->add(
                'doi',
                'text',
                array(
                    'label' => 'doi',
                    'required' => false,
                    'attr' => array('class' => ' form-control'),
                )
            )
            ->add(
                'otherId',
                'text',
                array(
                    'label' => 'otherid',
                    'required' => false,
                    'attr' => array('class' => ' form-control'),
                )
            )
            ->add(
                'isAnonymous',
                'radio',
                array(
                    'label' => 'isAnonymous',
                    'required' => false,
                )
            )
            ->add(
                'pubdate',
                'collot_datetime',
                array(
                    'label' => 'pubdate',
                    'date_format' => 'dd-MM-yyyy',
                    'pickerOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'startView' => 'month',
                        'minView' => 'month',
                        'todayBtn' => 'true',
                        'todayHighlight' => 'true',
                        'autoclose' => 'true',
                    ],
                )
            )
            ->add(
                'pubdateSeason',
                'text',
                array(
                    'label' => 'Pubdateseason',
                    'required' => false,
                    'attr' => array('class' => ' form-control'),
                )
            )
            ->add(
                'part',
                'text',
                array(
                    'label' => 'part',
                    'required' => false,
                    'attr' => array('class' => ' form-control'),
                )
            )
            ->add(
                'firstPage',
                'integer',
                array('label' => 'first_page', 'required' => false, 'attr' => array('class' => ' form-control'))
            )
            ->add(
                'lastPage',
                'integer',
                array(
                    'label' => 'last_page',
                    'required' => false,
                    'attr' => array('class' => ' form-control'),
                )
            )
            ->add(
                'uri',
                'text',
                array(
                    'label' => 'url',
                    'required' => false,
                    'attr' => array('class' => ' form-control'),
                )
            )
            ->add(
                'abstractTransliterated',
                'textarea',
                array(
                    'label' => 'abstractTransliterated',
                    'required' => false,
                    'attr' => array('class' => ' form-control'),
                )
            )
            ->add(
                'articleType',
                'entity',
                array(
                    'label' => 'article.type',
                    'class' => 'Ojs\JournalBundle\Entity\ArticleTypes',
                    'required' => false
                )
            )
            ->add('orderNum', 'integer', array('label' => 'order', 'required' => false))
            ->add(
                'submissionDate',
                'collot_datetime',
                array(
                    'label' => 'submissionDate',
                    'date_format' => 'dd-MM-yyyy',
                    'pickerOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'startView' => 'month',
                        'minView' => 'month',
                        'todayBtn' => 'true',
                        'todayHighlight' => 'true',
                        'autoclose' => 'true',
                    ],
                )
            )
            ->add('header', 'hidden')
            ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Entity\Article',
                'journal' => new Journal(),
                'attr' => [
                    'novalidate' => 'novalidate',
                    'class' => 'form-validate',
                ],
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_article';
    }
}
