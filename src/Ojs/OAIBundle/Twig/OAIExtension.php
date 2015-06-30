<?php

namespace Ojs\OAIBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class OAIExtension extends \Twig_Extension
{
    private $em;

    public function __construct(EntityManager $em = null)
    {
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
