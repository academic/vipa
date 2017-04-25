<?php

namespace Vipa\SiteBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuickSwitchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'journal',
            'entity',
            [
                'class' => 'Vipa\JournalBundle\Entity\Journal',
                'label_attr' => array('class' => 'sr-only'),
                'attr' => [
                    'class' => 'select2-element',
                    'placeholder' => 'Type a journal name to switch to its dashboard',
                ],
                'query_builder' => function (EntityRepository $er) {
                    $query = $er->createQueryBuilder('i');
                    return $query
                        ->andWhere('i.status = 1')
                        ->setCacheable(true);
                },
            ]

        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => null
            )
        );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'q';
    }

}
