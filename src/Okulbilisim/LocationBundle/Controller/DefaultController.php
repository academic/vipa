<?php

namespace Okulbilisim\LocationBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use Okulbilisim\LocationBundle\Entity\Location;
use Okulbilisim\LocationBundle\Entity\LocationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    public function citiesAction(Request $request, $country)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new NotFoundHttpException();
        }
        $em = $this->getDoctrine()->getManager();
        /** @var LocationRepository $cityRepo */
        $cityRepo = $em->getRepository('OkulbilisimLocationBundle:Location');

        /** @var Location[] $cities */
        $cities = $cityRepo->findBy(['parent_id' => $country, 'type' => '1']);

        $cities_array = [];
        foreach ($cities as $city) {
            $cities_array[] = [
                'id' => $city->getId(),
                'name' => $city->getName(),
            ];
        }

        return JsonResponse::create($cities_array);
    }
}
