<?php

namespace Ojs\JournalBundle\Form\Type;

use Ojs\JournalBundle\Entity\ArticleAuthor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleAuthorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author', new AuthorType())
            ->add(
                'correspondenceAuthor',
                CheckboxType::class,
                ['required' => false, 'label' => 'author.correspondence_author']
            )
            ->add('authorOrder', null, ['label' => 'author.order']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => ArticleAuthor::class,
                'cascade_validation' => true,
                'attr' => [
                    'class' => 'form-validate',
                ],
            )
        );
    }
}
