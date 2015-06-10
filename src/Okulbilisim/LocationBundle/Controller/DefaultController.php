<?php

namespace Okulbilisim\LocationBundle\Controller;

use Doctrine\ORM\EntityManager;
use Ojs\Common\Controller\OjsController as Controller;
use Okulbilisim\LocationBundle\Entity\CityRepository;
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
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var CityRepository $cityRepo */
        $cityRepo = $em->getRepository('OkulbilisimLocationBundle:City');

        $cities = $cityRepo->findBy(['country_id' => $country]);

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
