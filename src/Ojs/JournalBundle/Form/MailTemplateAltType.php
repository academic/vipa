<?php

namespace Ojs\JournalBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MailTemplateAltType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $journalId = $options['journal_id'];
        $builder
                ->add('journal', 'entity', array(
                    'class' => 'Ojs\JournalBundle\Entity\Journal',
                    'property' => 'title',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                    'query_builder' => function (EntityRepository $er) use ($journalId) {
                        $qb = $er->createQueryBuilder('a');
                        $qb->where(
                                $qb->expr()->eq('a.id', ':journalId')
                        );
                        $qb->setParameter('journalId', $journalId);
                        return $qb;
                    }
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
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\MailTemplate',
            'journal_id' => 0
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'ojs_journalbundle_mailtemplate_alt';
    }

}
