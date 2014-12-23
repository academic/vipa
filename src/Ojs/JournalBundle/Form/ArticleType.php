<?php

namespace Ojs\JournalBundle\Form;

use Doctrine\ORM\EntityRepository;
use Ojs\Common\Params\CommonParams;
use Ojs\UserBundle\Entity\Role;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $journal = $options['journal'];
        $user = $options['user'];
        $builder
                ->add('issue', 'entity', array(
                    'class' => 'Ojs\JournalBundle\Entity\Issue',
                    'required' => false,
                    'attr' => array('class' => ' form-control select2'),
                    'query_builder' => function (EntityRepository $er) use ($journal, $user) {
                $qb = $er->createQueryBuilder('i');
                foreach ($user->getRoles() as $role) {
                    /** @var Role $role */
                    if ($role->getRole() == 'ROLE_SUPER_ADMIN') {
                        return $qb;
                        break;
                    }
                }
                $qb->where(
                        $qb->expr()->eq('i.journalId', ':journal')
                )->setParameter('journal', $journal);
                return $qb;
            }
                ))
                ->add('status', 'choice', array(
                    'attr' => array('class' => ' form-control'),
                    'choices' => CommonParams::statusText()
                ))
                ->add('doi', 'text', array('required' => false, 'attr' => array('class' => ' form-control')))
                ->add('otherId', 'text', array('required' => false, 'attr' => array('class' => ' form-control')))
                ->add('keywords', 'text', array('attr' => array('class' => ' form-control')))
                ->add('journal', 'entity', array(
                    'attr' => array('class' => ' form-control select2'),
                    'class' => 'Ojs\JournalBundle\Entity\Journal',
                    'query_builder' => function(EntityRepository $er)use($journal, $user) {
                /** @var User $user $qb */
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
                ->setParameter('user', $user)
                ;
                return $qb;
            }
                ))
                ->add('title', 'text', array('attr' => array('class' => ' form-control')))
                ->add('titleTransliterated', 'text', array('required' => false, 'attr' => array('class' => ' form-control')))
                ->add('subtitle', 'text', array('required' => false, 'attr' => array('class' => ' form-control')))
                ->add('isAnonymous', 'radio', array('required' => false))
                ->add('orderNum', 'integer', array('required' => false))
                ->add('pubdate', 'date', array(
                    'required' => false,
                    'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
                    'attr' => array('class' => 'dateselector')
                ))
                ->add('submissionDate', 'date', array(
                    'required' => false,
                    'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
                    'attr' => array('class' => 'dateselector')
                ))
                ->add('pubdateSeason', 'text', array('required' => false, 'attr' => array('class' => ' form-control')))
                ->add('part', 'text', array('required' => false, 'attr' => array('class' => ' form-control')))
                ->add('firstPage', 'integer', array('required' => false, 'attr' => array('class' => ' form-control')))
                ->add('lastPage', 'integer', array('required' => false, 'attr' => array('class' => ' form-control')))
                ->add('uri', 'text', array('required' => false, 'attr' => array('class' => ' form-control')))
                ->add('abstract', 'textarea', array('required' => false, 'attr' => array('class' => ' form-control')))
                ->add('abstractTransliterated', 'textarea', array('required' => false, 'attr' => array('class' => ' form-control')))
                ->add('header', 'hidden')
                ->add('order', 'integer', array('required' => false));
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\Article',
            'journal' => 0,
            'user' => null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_article';
    }

}
