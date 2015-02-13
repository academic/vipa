<?php
/**
 * Date: 18.11.14
 * Time: 13:12
 * Devs: [
 *   ]
 */

namespace Ojs\SiteBundle\Twig;


use Doctrine\ODM\MongoDB\DocumentManager;
use Ojs\SiteBundle\Document\ImageOptions;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CommonExtension extends \Twig_Extension
{

    private $container;
    /**
     * @var DocumentManager
     */
    private $dm;

    public function __construct(ContainerInterface $container, DocumentManager $documentManager)
    {
        $this->dm = $documentManager;
        $this->container = $container;

    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('user', [$this, 'getUserById']),

        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('addFilters', [$this, 'addFilters']),
            new \Twig_SimpleFunction('getImageOptions', [$this, 'getImageOptions']),
            new \Twig_SimpleFunction('orcidLoginUrl', [$this, 'orcidLoginUrl']),
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
            if (empty($value))
                continue;
            $data[] = "{$key}=" . join('|', $value);
        }
        return join('&', $data);
    }

    private function parseFilters($filter)
    {
        return explode('|', $filter);
    }

    public function getImageOptions($entity, $type)
    {
        $document = $this->dm->getRepository('OjsSiteBundle:ImageOptions')
            ->findOneBy([
                'object' => get_class($entity),
                'object_id' => call_user_func([$entity, 'getId']),
                'image_type' => $type
            ]);
        if (!$document) {
            $null = $this->dm->getRepository('OjsSiteBundle:ImageOptions')
                ->init([
                    'height' => 0,
                    'width' => 0,
                    'x' => 0,
                    'y' => 0
                ], $entity, $type);
            return $null;
        }

        return $document;
    }

    public function orcidLoginUrl()
    {
        $orcid = $this->container->get('ojs.orcid_service');
        return $orcid->loginUrl();
    }

    public function getUserById($id)
    {
        $user = $this->container->get('doctrine')->getManager()->find('OjsUserBundle:User', $id);
        if (!$user)
            return null;
        return "{$user->getUsername()} ~ {$user->getEmail()} - {$user->getFirstName()} {$user->getLastName()}";
    }
}