<?php

namespace Ojs\JournalBundle\Form;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Ojs\JournalBundle\Entity\InstitutionTypes;
use Ojs\UserBundle\Entity\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Ojs\UserBundle\Entity\User;

class InstituteSuggestionType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var EntityManager $em */
        $em = $options['em'];
        $institutionTypes = $em->getRepository('OjsJournalBundle:InstitutionTypes')->findAll();
        $choices = [];
        foreach ($institutionTypes as $choice) {
            /** @var InstitutionTypes $choice*/
            $choices[$choice->getId()]=$choice->getName();
        }

        $builder
            ->add('name')
            ->add('slug')
            ->add('type','choice',[
                'choices'=>$choices
            ])
            ->add('about')
            ->add('address')
            ->add('city')
            ->add('country')
            ->add('lat')
            ->add('lon')
            ->add('email')
            ->add('fax')
            ->add('phone')
            ->add('url')
            ->add('wiki_url')
            ->add('tags')
            ->add('logo_image','hidden')
            ->add('header_image','hidden')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Document\InstituteSuggestion',
            'em'=>null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_institutesuggestion';
    }

}
