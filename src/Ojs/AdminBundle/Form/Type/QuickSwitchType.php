<?php

namespace Ojs\AdminBundle\Form\Type;

use Ojs\JournalBundle\Entity\JournalRepository;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class QuickSwitchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User $user */
        $user = $options['user'];
        $builder->add(
            'journal',
            'entity',
            [
                'class' => 'Ojs\JournalBundle\Entity\Journal',
                'label_attr' => array('class' => 'sr-only'),
                'attr' => [
                    'class' => 'select2-element',
                    'placeholder' => 'Type a journal name to switch to its dashboard',
                ],
                'query_builder' => function (EntityRepository $er) use ($user){
                    $query = $er->createQueryBuilder('i');
                    if(!$user->isAdmin()){
                        return $query
                            ->innerJoin('i.journalUsers','u')
                            ->andWhere('u.user = :user')
                            ->setParameter('user', $user);
                    }
                    return $query;
                },
            ]

        )->add('switch', 'submit', ['label' => 'switch']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => null,
                'user' => null
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
        return 'ojs_admin_quickswitch';
    }

}
