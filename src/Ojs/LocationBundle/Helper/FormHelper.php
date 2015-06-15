<?php

namespace Ojs\LocationBundle\Helper;

use Doctrine\ORM\EntityManager;
use Ojs\LocationBundle\Entity\Country;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class FormHelper
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function addCityField(FormBuilderInterface &$builder, $dataClass, $isMongo = false)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        $factory = $builder->getFormFactory();

        $refreshTown = function (FormInterface $form, $country) use ($factory, $em, $isMongo) {
            $childs = [];
            if (!empty($country)) {
                /** @var Country $country */
                $country = $em->find('OjsLocationBundle:Country', $country);
                foreach ($country->getProvinces() as $child) {
                    if ($child->getType() == 1) {
                        $childs[$child->getId()] = $isMongo === false ? $child : $child->getName();
                    }
                }
            }
            $options = [
                'empty_value' => 'Seçin',
                'choices' => $childs,
                'auto_initialize' => false,
                'label' => "Şehir",
                'attr' => [
                    'class' => 'select2-element',
                ],
            ];
            if ($isMongo === false) {
                $options['class'] = 'Ojs\LocationBundle\Entity\Province';
            }

            if (empty($cities)) {
                $options['attr']['disabled'] = 'disabled';
            }

            $form->add($factory->createNamed('city', $isMongo ? 'choice' : 'entity', null, $options));
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($refreshTown, $builder, $em, $dataClass) {
                $form = $event->getForm();
                $data = $event->getData();

                if ($data === null) {
                    return;
                }
                if (is_a($data, $dataClass)) {
                    if ($data->getCountry() instanceof Country) {
                        $refreshTown($form, $data->getCountry());
                    } else {
                        $refreshTown($form, null);
                    }
                }

            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($refreshTown) {
                $form = $event->getForm();
                $data = $event->getData();
                if (array_key_exists('country', $data)) {
                    $refreshTown($form, $data['country']);
                }
            }
        );
    }
}
