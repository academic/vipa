<?php

namespace Vipa\JournalBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Vipa\JournalBundle\Entity\JournalIndex;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournalIndexType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'index',
                'entity',
                [
                    'class' => 'Vipa\JournalBundle\Entity\Index',
                    'attr' => ['class' => ' form-control select2-element'],
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('i')
                            ->andWhere('i.status = :status')
                            ->setParameter('status', true);
                    },
                    'label' => 'journalindex.list'
                ]
            )
            ->add('link', 'url', array('label' => 'journalindex.link'));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => JournalIndex::class,
                'attr' => [
                    'class' => 'form-validate',
                ],
            ]
        );
    }
}
