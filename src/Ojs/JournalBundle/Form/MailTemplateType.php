<?php

namespace Ojs\JournalBundle\Form;

use Doctrine\ORM\EntityRepository;
use Ojs\UserBundle\Entity\Role;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MailTemplateType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User $user */
        $user = $options['user'];
        $builder
            ->add('journal', 'entity', [
                    'attr' => ['class' => ' form-control select2-element'],
                    'class' => 'Ojs\JournalBundle\Entity\Journal',
                    'query_builder' => function (EntityRepository $er) use ($user) {
                        /** @var User $user */
                        $qb = $er->createQueryBuilder('j');
                        foreach ($user->getRoles() as $role) {
                            /** @var Role $role */
                            if ($role->getRole() == 'ROLE_SUPER_ADMIN') {
                                return $qb;
                                break;
                            }
                        }

                        $qb
                            ->join('j.userRoles', 'user_role', 'WITH', 'user_role.user=:user')
                            ->setParameter('user', $user);
                        return $qb;
                    }
                    ,
                    'label' => 'mailtemplate.journal'
                ]
            )
            ->add('template', 'textarea', ['label' => 'mailtemplate.template','attr'=>['style'=>'height:200px']])
            ->add('type', 'text', ['label' => 'mailtemplate.type'])
            ->add('subject', 'text', ['label' => 'mailtemplate.subject'])
            ->add('lang', 'entity', [
                'class' => 'Ojs\JournalBundle\Entity\Lang',
                'property' => 'name',
                'label' => 'mailtemplate.language'
            ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\MailTemplate',
            'user' => null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_mailtemplate';
    }

}
