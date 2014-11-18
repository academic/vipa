<?php
/**
 * User: aybarscengaver
 * Date: 18.11.14
 * Time: 13:12
 * URI: www.emre.xyz
 * Devs: [
 * 'Aybars Cengaver'=>'aybarscengaver@yahoo.com',
 *   ]
 */

namespace Ojs\SiteBundle\Twig;



class CommonExtension extends \Twig_Extension {
    public function getFilters()
    {
        return [
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('addFilters',[$this,'addFilters'])
        ];
    }
    public function addFilters($key=null,$value=null)
    {
        $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
        $filters = $request->get('filters')?$request->get('filters'):[];
        if($key==null) return $filters;
        $filters[$key] = $value;
        return $filters;
    }
    public function getName()
    {
        return 'common_extension';
    }
}