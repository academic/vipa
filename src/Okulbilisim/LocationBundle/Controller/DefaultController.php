<?php

namespace Okulbilisim\LocationBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use Okulbilisim\LocationBundle\Entity\Country;
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

        /** @var Country $country */
        $country = $em->getRepository('OkulbilisimLocationBundle:Country')->find($country);
        $this->throw404IfNotFound($country);

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
