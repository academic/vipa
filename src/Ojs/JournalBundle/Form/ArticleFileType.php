<?php

namespace Ojs\JournalBundle\Form;

use Doctrine\ORM\EntityRepository;
use Ojs\Common\Params\ArticleFileParams;
use Ojs\UserBundle\Entity\Role;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleFileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $builder
            ->add('title')
            ->add('keywords')
            ->add('description')
            ->add('type','choice',[
                'choices'=>ArticleFileParams::$FILE_TYPES
            ])
            ->add('article', 'entity', array(
                'attr' => array('class' => ' form-control select2'),
                'class' => 'Ojs\JournalBundle\Entity\Article',
                'query_builder' => function(EntityRepository $er)use($user) {
                    /** @var User $user $qb */
                    $qb = $er->createQueryBuilder('a');
                    foreach ($user->getRoles() as $role) {
                        /** @var Role $role */
                        if ($role->getRole() == 'ROLE_SUPER_ADMIN') {
                            return $qb;
                            break;
                        }
                    }

                    $qb
                        ->join('a.journal.userRoles', 'user_role', 'WITH', 'user_role.user=:user')
                        ->setParameter('user', $user)
                    ;
                    return $qb;
                }
            )
            )
            ->add('langCode')
            ->add('version')
            ->add('article')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\ArticleFile',
            'user'=> null 
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_articlefile';
    }
}
