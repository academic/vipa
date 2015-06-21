<?php

namespace Ojs\LocationBundle\Form\EventListener;
use Ojs\LocationBundle\Entity\Province;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AddCountryFieldSubscriber implements EventSubscriberInterface
{
    private $provinceEndPoint;

    function __construct($provinceEndPoint)
    {
        $this->provinceEndPoint = $provinceEndPoint;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT   => 'preSubmit'
        );
    }

    private function addCountryForm(FormInterface $form, $country = null)
    {
        $formOptions = array(
            'class'         => 'Ojs\LocationBundle\Entity\Country',
            'mapped'        => false,
            'label'         => 'Country',
            'empty_value'   => 'Select Country',
            'attr'          => array(
                'class' => 'select2-element country_selector',
                'data-province-source' => $this->provinceEndPoint
            ),
        );

        if ($country) {
            $formOptions['data'] = $country;
        }

        $form->add('country', 'entity', $formOptions);
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $accessor = PropertyAccess::createPropertyAccessor();

        /** @var Province $province */
        $province   = $accessor->getValue($data, 'city');
        $country = ($province) ? $province->getCountry() : null;

        $this->addCountryForm($form, $country);
    }

    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();

        $this->addCountryForm($form);
    }
}
