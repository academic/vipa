<?php

namespace Ojs\UserBundle\Form;

use Doctrine\ORM\EntityRepository;
use Ojs\UserBundle\Entity\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserJournalRoleType extends AbstractType
{

    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $isAdmin = $this->container->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
        $journal = $this->container->get("ojs.journal_service")->getSelectedJournal();

        $builder->add('user', 'autocomplete', [
            'class' => 'Ojs\UserBundle\Entity\User',
            'label' => 'user',
            'multiple' => false,
            'expanded' => false,
            'attr' => [
                'class' => 'select2-element autocomplete validate[required]',
                'data-list' => $this->container->get('router')->generate('api_get_users'),
                'data-get' => $this->container->get('router')->generate('ojs_api_homepage') . 'public/user/get/',
                "placeholder" => "type a username"
            ]
        ])
            ->add('role', 'entity', [
                    'label' => 'role.singular',
                    'class' => 'Ojs\UserBundle\Entity\Role',
                    'property' => 'name',
                    'multiple' => false,
                    'expanded' => false,
                    'query_builder' => function (\Doctrine\ORM\EntityRepository $er) {
                        return $er->createQueryBuilder('ujr')
                            ->where('ujr.isSystemRole = 0');
                    },
                    'attr' => array("class" => "select2-element")
                ]
            );
        if ($isAdmin) {
            $builder->add('journal', 'autocomplete', [
                'class' => 'Ojs\JournalBundle\Entity\Journal',
                'label' => 'journal.singular',
                'attr' => [
                    'class' => 'autocomplete',
                    'data-list' => $this->container->get('router')->generate('ojs_api_homepage') . "public/search/journal",
                    'data-get' => $this->container->get('router')->generate('ojs_api_homepage') . "public/journal/get/",
                    "placeholder" => "type a journal name"
                ],
            ]);
        }else{

            $builder->add('journal', 'entity', [
                    'class' => 'Ojs\JournalBundle\Entity\Journal',
                    'query_builder' => function (\Doctrine\ORM\EntityRepository $er)use($journal) {
                        return $er->createQueryBuilder('ujr')
                            ->where('ujr.id = :journalId')
                            ->setParameter('journalId', $journal->getId());
                    }
                ]
            );
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\UserBundle\Entity\UserJournalRole',
            'user' => null,
            'attr' => [
                'novalidate' => 'novalidate'
                , 'class' => 'validate-form'
            ]
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_userbundle_userjournalrole';
    }

}
