<?php

namespace Vipa\CoreBundle\Service\Routing;

use Oneup\UploaderBundle\Routing\RouteLoader as BaseRouteLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RouteLoader extends BaseRouteLoader
{
    public function load($resource, $type = null)
    {
        $routes = new RouteCollection();

        foreach ($this->controllers as $type => $controllerArray) {

            $service = $controllerArray[0];
            $options = $controllerArray[1];

            $upload = new Route(
                sprintf('%s/_uploader/%s/upload', $options['route_prefix'], $type),
                array('_controller' => $service . ':upload', '_format' => 'json'),
                array(), array(), '', array(), array('POST', 'PUT', 'PATCH')
            );

            if ($options['enable_progress'] === true) {
                $progress = new Route(
                    sprintf('%s/_uploader/%s/progress', $options['route_prefix'], $type),
                    array('_controller' => $service . ':progress', '_format' => 'json'),
                    array(), array(), '', array(), array('POST', 'PUT', 'PATCH')
                );

                $routes->add(sprintf('_uploader_progress_%s', $type), $progress);
            }

            if ($options['enable_cancelation'] === true) {
                $progress = new Route(
                    sprintf('%s/_uploader/%s/cancel', $options['route_prefix'], $type),
                    array('_controller' => $service . ':cancel', '_format' => 'json'),
                    array(), array(), '', array(), array('POST', 'PUT', 'PATCH')
                );

                $routes->add(sprintf('_uploader_cancel_%s', $type), $progress);
            }

            $routes->add(sprintf('_uploader_upload_%s', $type), $upload);
        }

        return $routes;
    }
}
