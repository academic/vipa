<?php

namespace Ojs\CoreBundle\Controller;

use Ojs\CoreBundle\Controller\OjsController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Elastica\Query;

/**
 * Tags controller.
 *
 */
class TagsController extends Controller
{
    public function tagSearchAction(Request $request)
    {
        $q = $request->get('q');
        $search = $this->container->get('fos_elastica.index.search');

        $prefix = new Query\Prefix();
        $prefix->setPrefix('tags', strtolower($q));
        $qe = new Query();
        $qe->setQuery($prefix);

        $results = $search->search($prefix);
        $data = [];
        foreach ($results as $result) {
            foreach (explode(',', $result->getData()['tags']) as $tag) {
                $data[] = [
                    'id' => $tag,
                    'text' => $tag
                    ]
                ;
            }
        }

        return JsonResponse::create($data);
    }
}
