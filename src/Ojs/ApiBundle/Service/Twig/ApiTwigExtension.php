<?php

namespace Ojs\ApiBundle\Service\Twig;

use Symfony\Component\Routing\RouterInterface;

class ApiTwigExtension extends \Twig_Extension
{
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getApiViews', array($this, 'getApiViews'), array('is_safe' => array('html'))),
        );
    }

    /**
     * @return string
     */
    public function getApiViews()
    {
        $apiViews = [];
        $allRoutes = $this->router->getRouteCollection()->all();
        foreach ($allRoutes as $route) {
            $routeOptions = $route->getOptions();
            if(isset($routeOptions['api_view'])){
                if(!in_array($routeOptions['api_view'], $apiViews)){
                    $apiViews[] = $routeOptions['api_view'];
                }
            }
        }
        return $apiViews;
    }

    public function getName()
    {
        return 'api_twig_extension';
    }
}
