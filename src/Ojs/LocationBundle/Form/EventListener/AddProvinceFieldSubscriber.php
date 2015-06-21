<?php
namespace Ojs\LocationBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\EntityRepository;
use Ojs\LocationBundle\Entity\Province;

class AddProvinceFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::POST_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT    => 'preSubmit'
        );
    }
    private function addProvinceForm(FormInterface $form, $country_id)
    {
        $formOptions = array(
            'class'         => 'Ojs\LocationBundle\Entity\Province',
            'empty_value'   => 'City',
            'label'         => 'City',
            'attr'          => array(
                'class' => 'select2-element province_selector',
            ),
            'query_builder' => function (EntityRepository $repository) use ($country_id) {
                $qb = $repository->createQueryBuilder('p')
                    ->innerJoin('p.country', 'country')
                    ->where('country.id = :country')
                    ->setParameter('country', $country_id)
                ;
                return $qb;
            }
        );
        $form->add('city', 'entity', $formOptions);
    }
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
        if (null === $data) {
            return;
        }
        $accessor    = PropertyAccess::createPropertyAccessor();
        /** @var Province $province */
        $province       = $accessor->getValue($data, 'city');
        $country_id = ($province) ? $province->getCountry()->getId() : null;
        $this->addProvinceForm($form, $country_id);
    }
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
        $country_id = array_key_exists('country', $data) ? $data['country'] : null;
        $this->addProvinceForm($form, $country_id);
    }
}
