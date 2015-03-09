<?php

namespace Ojs\JournalBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JournalContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $builder
                ->add('journal', 'entity', array(
                    'attr' => array('class' => ' form-control'),
                    'class'=>'Ojs\JournalBundle\Entity\Journal',
                    'query_builder'=>function(EntityRepository $er)use($user){
                        $qb = $er->createQueryBuilder('j');
                        $qb
                            ->join('j.userRoles','user_role','WITH','user_role.user=:user')
                            ->setParameter('user',$user)
                        ;
                        return $qb;
                    }
                ))
                ->add('contact')
                ->add('contactType')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\JournalContact',
            'user'=>null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_journalcontact';
    }

}
