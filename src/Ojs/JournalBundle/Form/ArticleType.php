<?php

namespace Ojs\JournalBundle\Form;

use Doctrine\ORM\EntityRepository;
use Ojs\Common\Params\CommonParams;
use Ojs\UserBundle\Entity\Role;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType extends AbstractType {

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
                    'label' => 'issue',
                    'class' => 'Ojs\JournalBundle\Entity\Issue',
                    'required' => false,
                    'attr' => array('class' => ' form-control select2-element'),
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
                    'label' => 'status',
                    'attr' => array('class' => ' form-control'),
                    'choices' => CommonParams::statusText()
                ))
                ->add('doi', 'text', array(
                    'label' => 'doi',
                    'required' => false, 'attr' => array('class' => ' form-control'))
                )
                ->add('otherId', 'text', array(
                    'label' => 'otherid',
                    'required' => false, 'attr' => array('class' => ' form-control'))
                )
                ->add('keywords', 'text', array(
                    'label' => 'keywords',
                    'attr' => array('class' => ' form-control'))
                )
                ->add('journal', 'entity', array(
                    'label' => 'journal',
                    'attr' => array('class' => ' form-control select2-element'),
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
                ->add('title', 'text', array(
                    'label' => 'title',
                    'attr' => array('class' => ' form-control'))
                )
                ->add('titleTransliterated', 'text', array(
                    'label' => 'titleTransliterated',
                    'required' => false, 'attr' => array('class' => ' form-control'))
                )
                ->add('subtitle', 'text', array(
                    'label' => 'subtitle',
                    'required' => false, 'attr' => array('class' => ' form-control'))
                )
                ->add('isAnonymous', 'radio', array(
                    'label' => 'isAnonymous', 'required' => false)
                )
                ->add('orderNum', 'integer', array('label' => 'order', 'required' => false))
                ->add('pubdate', 'collot_datetime', array(
                    'label' => 'pubdate',
                    'date_format' => 'yyyy-MM-dd',
                ))
                ->add('submissionDate', 'collot_datetime', array(
                    'label' => 'submissionDate', 'date_format' => 'yyyy-MM-dd',)
                )
                ->add('pubdateSeason', 'text', array(
                    'label' => 'Pubdateseason', 'required' => false, 'attr' => array('class' => ' form-control'))
                )
                ->add('part', 'text', array(
                    'label' => 'part', 'required' => false, 'attr' => array('class' => ' form-control'))
                )
                ->add('firstPage', 'integer', array('label' => 'first_page', 'required' => false, 'attr' => array('class' => ' form-control'))
                )
                ->add('lastPage', 'integer', array(
                    'label' => 'last_page', 'required' => false, 'attr' => array('class' => ' form-control'))
                )
                ->add('uri', 'text', array(
                    'label' => 'url', 'required' => false, 'attr' => array('class' => ' form-control'))
                )
                ->add('abstract', 'textarea', array(
                    'label' => 'abstract', 'required' => false, 'attr' => array('class' => ' form-control wysihtml5'))
                )
                ->add('abstractTransliterated', 'textarea', array('label' => 'abstractTransliterated', 'required' => false, 'attr' => array('class' => ' form-control')))
                ->add('header', 'hidden')
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
            'user' => null,
            'attr' => [
                'novalidate' => 'novalidate'
                , 'class' => 'form-validate'
            ]
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
