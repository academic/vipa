<?php

namespace Ojs\JournalBundle\Form\Type;

use Ojs\JournalBundle\Entity\ArticleSubmissionStart;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\EqualTo;

class ArticleStartType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('articleSubmissionFiles', 'collection', array(
                    'type' => new ArticleSubmissionFileType(),
                    'allow_add' => false,
                    'allow_delete' => false,
                    'options' => array(
                    )
                )
            )
            ->add(
                'checks',
                'choice',
                array(
                    'multiple' => true,
                    'expanded' => true,
                    'choice_list' => new ArrayChoiceList(
                        $options['checkListsChoices'],
                        null
                    ),
                    'constraints' => array(
                        new EqualTo(
                            array(
                                'value' => array_values($options['checkListsChoices']),
                                'message' => 'all.fields.must.be.selected'
                            )
                        )
                    )
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
                'data_class' => ArticleSubmissionStart::class,
                'checkListsChoices' => [],
                'submissionFilesChoices' => [],
                'attr' => [
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
        return 'ojs_article_submission';
    }
}
