<?php
namespace Ojs\JournalBundle\Form\Type;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class JournalDesignType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', [
                'label' => 'Title'
                ]
            )
            ->add('editableContent', 'hidden')
            ->add('isPublic', 'checkbox', [
                'label' => 'ojs.is_public'
                ]
            )
        ;
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
            )
        );
    }
    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_journaldesign';
    }
}