<?php

namespace Ojs\JournalBundle\Form;

use Doctrine\ORM\EntityManager;
use Ojs\JournalBundle\Entity\Institution;
use Okulbilisim\LocationBundle\Entity\City;
use Okulbilisim\LocationBundle\Entity\Country;
use Okulbilisim\LocationBundle\Helper\FormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InstitutionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var FormHelper $helper */
        $helper = $options['helper'];
        $builder
            ->add('name', 'text', ['required' => true])
            ->add('slug', 'text', [
                'required' => true,
            ])
            ->add('institution_type', 'entity', [
                'class' => 'Ojs\JournalBundle\Entity\InstitutionTypes'
            ])
            ->add('about')
            ->add('address')
            ->add('addressLat')
            ->add('addressLong')
            ->add('phone')
            ->add('fax')
            ->add('email')
            ->add('url')
            ->add('wiki')
            ->add('logo', 'hidden')
            ->add('header', 'hidden')
            ->add('country', 'entity', [
                'class' => 'Okulbilisim\LocationBundle\Entity\Country',
                'attr' => [
                    'class' => 'select2-element  bridged-dropdown',
                    'data-to'=>'#'.$this->getName().'_city'
                ]
            ]);
        $helper->addCityField($builder,'Ojs\JournalBundle\Entity\Institution');

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Entity\Institution',
            'helper' => null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_journalbundle_institution';
    }
}
