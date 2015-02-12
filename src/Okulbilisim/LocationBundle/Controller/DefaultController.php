<?php

namespace Okulbilisim\LocationBundle\Controller;

use Doctrine\ORM\EntityManager;
use Okulbilisim\LocationBundle\Entity\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    public function citiesAction(Request $request, $country)
    {
        if (!$request->isXmlHttpRequest())
            throw new NotFoundHttpException;
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var CityRepository $cityRepo */
        $cityRepo = $em->getRepository('OkulbilisimLocationBundle:City');

        $cities = $cityRepo->findBy(['country_id' => $country]);

        $cities_array = [];
        foreach ($cities as $city) {
            $cities_array[] = [
                'id' => $city->getId(),
                'name' => $city->getName()
            ];
        }
        return JsonResponse::create($cities_array);
    }
}
