<?php

namespace Ojs\UserBundle\Form;

use Ojs\Common\Params\CommonParams;
use Okulbilisim\LocationBundle\Helper\FormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username','text',[
                'attr'=>[
                    'class'=>'validate[required]'
                ]
            ])
            ->add('password', 'password', [])
            ->add('email','email',[
                'attr'=>[
                    'class'=>'validate[required,custom[email]]'
                ]
            ])
            ->add('title')
            ->add('firstName','text',[
                'attr'=>[
                    'class'=>'validate[required]'
                ]
            ])
            ->add('lastName','text',[
                'attr'=>[
                    'class'=>'validate[required]'
                ]
            ])
            ->add('status', 'choice', [
                'choices' => CommonParams::$userStatusArray
            ])
            ->add('roles', 'entity', array(
                'class' => 'Ojs\UserBundle\Entity\Role',
                'property' => 'name',
                'multiple' => true,
                'expanded' => false,
                'attr' => array('class' => 'select2-element', 'style' => 'width:100%')
            ))
            ->add('subjects', 'entity', array(
                'class' => 'Ojs\JournalBundle\Entity\Subject',
                'property' => 'subject',
                'multiple' => true,
                'expanded' => false,
                'attr' => array('class' => 'select2-element', 'style' => 'width:100%'),
                'required' => false
            ))
            ->add('avatar', 'hidden')
            ->add('header', 'hidden')
            ->add('country', 'entity', [
                'class' => 'Okulbilisim\LocationBundle\Entity\Country',
                'attr' => [
                    'class' => 'select2-element  bridged-dropdown',
                    'data-to' => '#' . $this->getName() . '_city'
                ]
            ]);
        /** @var FormHelper $helper */
        $helper = $options['helper'];
        $helper->addCityField($builder, 'Ojs\UserBundle\Entity\User');;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Ojs\UserBundle\Entity\User',
            'helper' => null,
            'attr'=>[
                'class'=>'validate-form',
                'novalidate'=>'novalidate'
            ]
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ojs_userbundle_user';
    }

}
