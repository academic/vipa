<?php

namespace Ojs\JournalBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BoardType extends AbstractType
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
            ->add(
                'journal',
                'entity',
                array(
                    'attr' => array('class' => ' form-control select2-element'),
                    'label' => 'journal',
                    'class' => 'Ojs\JournalBundle\Entity\Journal',
                    'query_builder' => function (EntityRepository $er) use ($journal) {
                        $qb = $er->createQueryBuilder('j');
                        if ($journal) {
                            $qb->where(
                                $qb->expr()->eq('j.id', ':journal')
                            )->setParameter('journal', $journal);
                        }

                        return $qb;
                    },
                )
            )
            ->add('name', 'text', ['label' => 'name'])
            ->add('description', 'textarea', ['label' => 'description', 'attr' => ['class' => 'editor', 'rows' => 5]]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'journal' => null,
                'data_class' => 'Ojs\JournalBundle\Entity\Board',
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_board';
    }
}
