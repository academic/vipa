<?php

namespace Ojs\JournalBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalsIndex;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournalsIndexType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User $user */
        $user = $options['user'];
        //$journal = $options['journal'];
        $builder
            ->add(
                'journal_index',
                'entity',
                [
                    'class' => 'Ojs\JournalBundle\Entity\JournalIndex',
                    'attr' => ['class' => ' form-control select2-element'],
                ]
            );
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($user) {
                /** @var JournalsIndex $journalsindex */
                $journalsindex = $event->getData();
                $form = $event->getForm();
                if (null === $journalsindex->getJournalId()) {
                    $form->add(
                        'journal',
                        'entity',
                        [
                            'attr' => ['class' => ' form-control select2-element'],
                            'label' => 'journal',
                            'class' => 'Ojs\JournalBundle\Entity\Journal',
                            'query_builder' => function (EntityRepository $er) use ($user) {
                                /** @var User $user $qb */
                                $qb = $er->createQueryBuilder('j');
                                if ($user->isAdmin()) {
                                    return $qb;
                                }
                                $qb
                                    ->join('j.userRoles', 'user_role', 'WITH', 'user_role.user=:user')
                                    ->setParameter('user', $user);

                                return $qb;
                            },
                        ]
                    );
                } else {
                    $form->add('journal_id', 'hidden');
                }
            }
        );
        $builder->add('link', 'text', array('label' => 'journalsindex.link'));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Ojs\JournalBundle\Entity\JournalsIndex',
                'user' => null,
                'journal' => null,
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
        return 'ojs_journalbundle_journalsindex';
    }
}
