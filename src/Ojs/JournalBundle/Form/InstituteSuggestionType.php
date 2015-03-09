<?php

namespace Ojs\JournalBundle\Form;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Ojs\JournalBundle\Entity\InstitutionTypes;
use Ojs\UserBundle\Entity\Role;
use Okulbilisim\LocationBundle\Helper\FormHelper;
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

        /** @var FormHelper $helper */
        $helper = $options['helper'];
        /** @var EntityManager $em */
        $em = $options['em'];
        $institutionTypes = $em->getRepository('OjsJournalBundle:InstitutionTypes')->findAll();
        $choices = [
            'types'=>[],
            'countries'=>[]
        ];
        foreach ($institutionTypes as $choice) {
            /** @var InstitutionTypes $choice*/
            $choices['types'][$choice->getId()]=$choice->getName();
        }
        $countries = $em->getRepository('OkulbilisimLocationBundle:Country')->findAll();
        foreach ($countries as $country) {
            $choices['countries'][$country->getId()]=$country->getName();
        }


        $builder
            ->add('name')
            ->add('slug')
            ->add('type','choice',[
                'choices'=>$choices['types']
            ])
            ->add('about')
            ->add('address')
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

            ->add('country', 'choice', [
                'choices' => $choices['countries'],
                'attr' => [
                    'class' => 'select2-element  bridged-dropdown',
                    'data-to'=>'#'.$this->getName().'_city'
                ]
            ]);
        $helper->addCityField($builder,'Ojs\JournalBundle\Document\InstituteSuggestion',true);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ojs\JournalBundle\Document\InstituteSuggestion',
            'em'=>null,
            'helper' => null

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
