<?php

namespace Ojs\JournalBundle\Form\Type;

use Ojs\CoreBundle\Form\Type\JournalBasedTranslationsType;
use Ojs\JournalBundle\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('orcid')
            ->add('translations', JournalBasedTranslationsType::class, [
                'label' => ' ',
                'required' => false,
                'fields' => [
                    'biography' => [
                        'label' => 'author.biography'
                    ]
                ]
            ])
            ->add('title', null, [
                'label'     => 'user.title',
                'required'  => false,
            ])
            ->add('middleName', null, [
                'required' => false,
                'label' => 'author.middlename'
            ])
            ->add('firstName', null, ['label' => 'author.firstname'])
            ->add('lastName', null, ['label' => 'author.lastname'])
            ->add('phone', null, [
                'required' => false,
                'label' => 'author.phone'
            ])
            ->add('firstNameTransliterated', null, [
                'required' => false,
                'label' => 'author.firstnametransliterated'
            ])
            ->add('middleNameTransliterated', null, [
                'required' => false,
                'label' => 'author.middlenametransliterated'
            ])
            ->add('lastNameTransliterated', null,[
                'required' => false,
                'label' => 'author.lastnametransliterated'
            ])
            ->add('initials', null, [
                'required' => false,
                'label' => 'author.initials'
            ])
            ->add('email', 'email', ['label' => 'author.email'])
            ->add('address', 'textarea', [
                'required' => false,
                'label' => 'author.address'
            ])
            ->add('institution', null, [
                'attr' => [
                    'class' => 'institution'
                ],
                'label' => 'author.institution'
            ])
            ->add('institutionNotListed', null, [
                'attr' => [
                    'class' => 'institutionNotListed'
                ],
                'label' => 'author.institution_not_listed'
            ])
            ->add('institutionName', null, [
                'attr' => [
                    'class' => 'institutionName'
                ],
                'label' => 'institute.name'
            ])
            ->add('country', null ,[
                'required' => false,
                'attr' => [
                    'class' => "select2-element"
                ],
                'label' => 'country'
            ])
            ->add('authorDetails', 'textarea', [
                'required' => false
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Author::class,
                'cascade_validation' => true,
                'attr' => [
                    'class' => 'form-validate',
                ],
            )
        );
    }
}
