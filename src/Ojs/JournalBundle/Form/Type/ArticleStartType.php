<?php

namespace Ojs\JournalBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;

class ArticleStartType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'competingFile',
                'jb_file_ajax',
                array(
                    'endpoint' => 'articlefiles',
                    'label' => 'workflow.competing_of_interest_file',
                    'constraints' => array(
                        new NotBlank()
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
                                'message' => 'All fields must be selected'
                            )
                        )
                    )
                )
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'checkListsChoices' => [],
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
        return 'ojs_article_submission';
    }
}
