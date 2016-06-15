<?php

namespace Ojs\AdminBundle\Form\Type;

use Ojs\JournalBundle\Entity\Subject;
use Ojs\JournalBundle\Entity\SubjectRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubjectType extends AbstractType
{
    private $selfId;

    /**
     * @param integer|null $selfId
     */
    public function __construct($selfId = null)
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
                    'empty_data'  => null,
                    'query_builder' => function (SubjectRepository $repository) use ($selfId) {
                        $query = $repository
                            ->createQueryBuilder('subject');
                        if ($selfId !== null) {
                            return $query
                                ->andWhere('subject.id != :selfId')
                                ->setParameter('selfId', $selfId);
                        }
                        return $query;
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
                'data_class' => Subject::class,
                'cascade_validation' => true,
                'attr' => [
                    'class' => 'form-validate',
                ],
            )
        );
    }
}
