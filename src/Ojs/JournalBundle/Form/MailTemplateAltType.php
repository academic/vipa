<?php

namespace Ojs\JournalBundle\Form;

use Doctrine\ORM\EntityRepository;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MailTemplateAltType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('journal', 'entity', array(
                    'class' => 'Ojs\JournalBundle\Entity\Journal',
                    'property' => 'title',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                    'query_builder' => function (EntityRepository $er) use ($options) {
                        $qb = $er->createQueryBuilder('j');
                        if($options['journal'] instanceof Journal) {
                            $qb->andWhere('j.id = :journalId')
                                ->setParameter('journalId', $options['journal']->getId());
                        }
                        return $qb;
                    },
                )
            )
            ->add('template')
            ->add('type')
            ->add('subject')
            ->add('lang')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\MailTemplate',
            'journal' => false,
            'attr' => [
                'novalidate' => 'novalidate', 'class' => 'form-validate',
            ],
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_mailtemplate_alt';
    }
}
