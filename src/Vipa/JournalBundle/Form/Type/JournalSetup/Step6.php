<?php

namespace Vipa\JournalBundle\Form\Type\JournalSetup;

use Doctrine\ORM\EntityRepository;
use Vipa\JournalBundle\Entity\Journal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Step6 extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'theme',
                'entity',
                array(
                    'class' => 'Vipa\JournalBundle\Entity\JournalTheme',
                    'property' => 'title',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                    'attr' => [
                        'class' => 'validate[required]',
                    ],
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('t')
                            ->where('t.public IS NULL OR t.public = TRUE');
                    },
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
                'validation_groups' => ['setupStep6'],
                'data_class' => Journal::class,
                'attr' => [
                    'novalidate' => 'novalidate',
                    'class' => 'form-validate',
                ],
            )
        );
    }
}
