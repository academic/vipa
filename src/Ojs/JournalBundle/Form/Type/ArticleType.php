<?php

namespace Ojs\JournalBundle\Form\Type;

use GuzzleHttp\Client;
use Ojs\CoreBundle\Form\Type\JournalBasedTranslationsType;
use Ojs\CoreBundle\Params\DoiStatuses;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Article $entity */
        $entity = $builder->getData();
        $disabled = false;
        if($entity !== null && $entity->getDoiStatus() == DoiStatuses::VALID && $options['doiDisabled']){
            $disabled = true;
        }
        $form = $builder
            ->add('translations', JournalBasedTranslationsType::class,[
                'fields' => [
                    'title' => [
                        'label' => 'article.title',
                        'field_type' => 'text'
                    ],
                    'keywords' => [
                        'label' => 'keywords',
                        'field_type' => 'tags'
                    ],
                    'abstract' => [
                        'required' => false,
                        'attr' => array('class' => ' form-control wysihtml5'),
                        'field_type' => 'purified_textarea'
                    ]
                ]
            ])
            ->add(
                'language',
                'entity',
                [
                    'label' => 'article.language.primary',
                    'class' => 'Ojs\JournalBundle\Entity\Lang',
                    'attr' => [
                        'class' => 'select2-element ',
                    ],
                    'choices' => $options['journal']->getLanguages(),
                ]
            )
            ->add(
                'subjects',
                'entity',
                array(
                    'class' => 'OjsJournalBundle:Subject',
                    'multiple' => true,
                    'required' => true,
                    'property' => 'indentedSubject',
                    'label' => 'subjects',
                    'attr' => [
                        'style' => 'height: 100px'
                    ],
                    'choices' => $options['journal']->getSubjects(),
                )
            )
            ->add('titleTransliterated', null, [
                'label' => 'article.titleTransliterated'
                ]
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
                'doi',
                'text',
                array(
                    'label' => 'doi',
                    'required' => false,
                    'attr' => array('class' => ' form-control'),
                    'disabled' => $disabled,
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
                'pubdate',
                'collot_datetime',
                array(
                    'required' => false,
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
                'articleType',
                'entity',
                array(
                    'label' => 'article.type',
                    'class' => 'Ojs\JournalBundle\Entity\ArticleTypes',
                    'required' => false,
                    'choices' => $options['journal']->getArticleTypes()
                )
            )
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
            ->add(
                'acceptanceDate',
                'collot_datetime',
                [
                    'required'      => false,
                    'label'         => 'article.acceptance',
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
            ;
        $form->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            /** @var Article $article */
            $article = $event->getForm()->getData();
            if(isset($data['doi']) && $data['doi'] !== $article->getDoi()) {

                try {
                    $client = new Client();
                    $client->get('http://doi.org/api/handles/'.$data['doi']);
                    $article->setDoiStatus(DoiStatuses::VALID);
                } catch(\Exception $e) {
                    $article->setDoi(null);
                }
            }
        });
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'journal' => new Journal(),
                'data_class' => Article::class,
                'cascade_validation' => true,
                'attr' => [
                    'class' => 'form-validate',
                ],
                'doiDisabled' => true
            )
        );
    }
}
