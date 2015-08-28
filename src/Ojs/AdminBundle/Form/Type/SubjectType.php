<?php

namespace Ojs\AdminBundle\Form\Type;

use Ojs\JournalBundle\Entity\SubjectRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubjectType extends AbstractType
{
    private $selfId;

    function __construct($selfId = null)
    {
        $this->selfId = $selfId;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $selfId = $this->selfId;
        
        $builder
            ->add('translations', 'a2lix_translations')
            ->add('tags', 'tags')
            ->add(
                'parent',
                'entity',
                array(
                    'class' => 'OjsJournalBundle:Subject',
                    'label' => 'parent',
                    'required' => false,
                    'placeholder' => 'none',
                    'empty_data'  => null,
                    'query_builder' => function (SubjectRepository $repository) use ($selfId) {
                        if ($selfId != null) {
                            return $repository
                                ->createQueryBuilder('subject')
                                ->andWhere('subject.id != :selfId')
                                ->setParameter('selfId', $selfId);
                        }
                    }
                )
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Ojs\JournalBundle\Entity\Subject',
                'cascade_validation' => true,
                'attr' => [
                    'class' => 'form-validate',
                ],
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_subject';
    }
}
