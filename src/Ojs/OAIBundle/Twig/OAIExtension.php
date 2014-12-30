<?php
/**
 * Date: 30.12.14
 * Time: 15:39
 */
namespace Ojs\OAIBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class OAIExtension extends \Twig_Extension
{
    private $container;
    private $em;

    public function __construct(ContainerInterface $container = null, \Doctrine\ORM\EntityManager $em = null)
    {
        $this->container = $container;
        $this->em = $em;
    }

    public function getFunctions()
    {
        return [
            'metadataPrefix' => new \Twig_Function_Method($this, 'metadataPrefix'),
        ];

    }

    public function metadataPrefix()
    {
        $request = Request::createFromGlobals();
        $metadataPrefix = $request->get('metadataPrefix', 'oai_dc');
        return $metadataPrefix;
    }

    public function getname()
    {
        return 'oai_extension';
    }
}