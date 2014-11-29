<?php
/**
 * Date: 18.11.14
 * Time: 13:12
 * Devs: [
 *   ]
 */

namespace Ojs\SiteBundle\Twig;


class CommonExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('addFilters', [$this, 'addFilters'])
        ];
    }

    public function addFilters($key = null, $value = null)
    {
        $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
        $filters = [];
        $filters['institution'] = $request->get('institution') ? $this->parseFilters($request->get('institution')) : [];
        $filters['subject'] = $request->get('subject') ? $this->parseFilters($request->get('subject')) : [];
        if (isset($filters[$key]) && count($filters[$key]) > 4) {
            unset($filters[$key][0]);
        }
        $filters[$key][] = $value;
        return $this->convertToUrl($filters);
    }

    public function getName()
    {
        return 'common_extension';
    }

    private function convertToUrl($filters = [])
    {
        $data = [];
        foreach ($filters as $key => $value) {
            if(empty($value))
                continue;
            $data[] = "{$key}=" . join('|', $value);
        }
        return join('&',$data);
    }

    private function parseFilters($filter)
    {
        return explode('|', $filter);
    }
}