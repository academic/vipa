<?php

namespace Ojs\CmsBundle\Twig;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\MappingException;
use Ojs\CmsBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PostExtension  extends \Twig_Extension{

    /** @var  ContainerInterface */
    private $container;
    /** @var  EntityManager */
    private $em;
    public function __construct($container,$em)
    {
        $this->container = $container;
        $this->em = $em;
    }

    public function getFilters(){
        return [
            new \Twig_SimpleFilter('post_status',[$this,'status']),
            new \Twig_SimpleFilter('cmsobject',[$this,'cmsobject'])

        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getPostObject',[$this,'post_object']),
        ];
    }

    public function status($key)
    {
        $statuses = [
            0=>'Disable',
            1=>'Enable',
            2=>'Canceled'
        ];
        if(isset($statuses[$key]))
            return $statuses[$key];
        return $key;
    }

    public function cmsobject($object)
    {
        switch(gettype($object)){
            case 'string';
                return '';
            case 'object';
                return $this->encode($object);
        }
    }

    public function getobject($object, $id)
    {
        $repo = $this->em->find($object,$id);
    }
    /**
     * Basic encoding
     * @param $string
     * @return string
     */
    public function encode($string)
    {
        $string = $this->getEntityName($string);
        $string = base64_encode($string);
        $len = strlen($string);
        $piece = $len/2;
        $encoded = substr($string,$piece,$len-1).substr($string,0,$piece);
        return $encoded;
    }

    /**
     * Basic encoding
     * @param $string
     * @return string
     */
    public function decode($string)
    {
        $len = strlen($string);
        $piece = $len/2;
        $string = substr($string,$piece,$len-1).substr($string,0,$piece);
        $decoded = base64_decode($string);
        return $decoded;
    }
    public function post_object(Post $post)
    {
        $object = $post->getObject();
        $id = $post->getObjectId();
        if(!$object) return '';
        $objectClass = $this->decode($object);
        $object = $this->em->find($objectClass,$id);
        $cms_routes = $this->container->getParameter('cms_show_routes');
        /** @var Router $router */
        $router = $this->container->get('router');
        $objectRoute = $cms_routes[$objectClass];
        $parameters = [];
        foreach ($objectRoute['parameters'] as $key) {
            $value = $post->{'get'.ucfirst($key)}();
            $parameters[$key]=$value;
        }

        $route = $router->generate($objectRoute['name'],$parameters);
        return '<a href="'.$route.'" target="_blank">'.$object.'</a>';
    }

    public function getName()
    {
        return 'post_extension';
    }
    /**
     * Returns Doctrine entity name
     *
     * @param mixed $entity
     *
     * @return string
     * @throws \Exception
     */
    private function getEntityName($entity)
    {
        try {
            $entityName = $this->em->getMetadataFactory()->getMetadataFor(get_class($entity))->getName();
        } catch (MappingException $e) {
            throw new \Exception('Given object ' . get_class($entity) . ' is not a Doctrine Entity. ');
        }

        return $entityName;
    }
}