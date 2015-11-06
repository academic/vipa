<?php

namespace Ojs\JournalBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
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
                    'class' => 'Ojs\JournalBundle\Entity\Index',
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
                'data_class' => 'Ojs\JournalBundle\Entity\JournalIndex',
                'attr' => [
                    'class' => 'form-validate',
                ],
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_journalindex';
    }
}
