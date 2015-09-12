<?php

namespace Ojs\LocationBundle\Controller;

use Ojs\LocationBundle\Entity\Country;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function citiesAction($country)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Country $country */
        $country = $em->getRepository('OjsLocationBundle:Country')->find($country);
        if (!$country) {
            throw $this->createNotFoundException();
        }

        $cities_array = [];
        foreach ($country->getProvinces() as $city) {
            $cities_array[] = [
                'id' => $city->getId(),
                'name' => $city->getName(),
            ];
        }

        return JsonResponse::create($cities_array);
    }
}
