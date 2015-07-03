<?php
namespace Ojs\SiteBundle\Twig;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;
use Ojs\Common\Services\OrcidService;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class CommonExtension extends \Twig_Extension
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var DocumentManager
     */
    private $dm;

    /**
     * @var OrcidService
     */
    private $orcidService;

    /**
     * @var FilterManager
     */
    private $filterManager;

    /**
     * @param EntityManagerInterface $entityManager
     * @param DocumentManager        $documentManager
     * @param OrcidService           $orcidService
     * @param FilterManager          $filterManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        DocumentManager $documentManager,
        OrcidService $orcidService,
        FilterManager $filterManager
    ) {
        $this->em = $entityManager;
        $this->dm = $documentManager;
        $this->orcidService = $orcidService;
        $this->filterManager = $filterManager;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('user', [$this, 'getUserByIdOrUsername']),

        ];
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getImageOptions', [$this, 'getImageOptions']),
            new \Twig_SimpleFunction('orcidLoginUrl', [$this, 'orcidLoginUrl']),
        ];
    }



    /**
     * @param $entity
     * @param $type
     * @param  null        $filter
     * @return array|mixed
     */
    public function getImageOptions($entity, $type, $filter = null)
    {
        $options = $entity->{'get'.ucfirst($type).'Options'}();
        $options = json_decode($options, true);
        if (
            !$options ||
            ((array_key_exists('height', $options) && $options['height'] < 1) ||
                (array_key_exists('width', $options) && $options['width'] < 1))
        ) {
            $defaultConfig = [
                'height' => 0,
                'width' => 0,
                'x' => 0,
                'y' => 0,
            ];

            if ($filter) {
                $config = $this->filterManager->getFilterConfiguration()->get($filter);
                if (isset($config['filters']) && isset($config['filters']['crop']) && isset($config['filters']['crop']['size'])) {
                    $size = $config['filters']['crop']['size'];
                    $defaultConfig['height'] = $size[1];
                    $defaultConfig['width'] = $size[0];
                }
                if (isset($config['filters']) && isset($config['filters']['crop']) && isset($config['filters']['crop']['start'])) {
                    $start = $config['filters']['crop']['start'];
                    $defaultConfig['x'] = $start[0];
                    $defaultConfig['y'] = $start[1];
                }
            }

            return $defaultConfig;
        }

        return $options;
    }

    /**
     * @return string
     */
    public function orcidLoginUrl()
    {
        return $this->orcidService->loginUrl();
    }

    /**
     * @param $id
     * @param  bool        $object
     * @return User|string
     */
    public function getUserByIdOrUsername($id, $object = false)
    {
        if (empty($id)) {
            return '';
        }

        if (is_numeric($id)) {
            /** @var User $user */
            $user = $this->em->getRepository('OjsUserBundle:User')->find($id);
        } else {
            /** @var User $user */
            $user = $this->em->getRepository('OjsUserBundle:User')->findOneBy(array('username' => $id));
        }

        if (!$user) {
            return '';
        }
        if ($object) {
            return $user;
        }

        return "{$user->getUsername()} ~ {$user->getEmail()} - {$user->getFirstName()} {$user->getLastName()}";
    }

    public function getName()
    {
        return 'common_extension';
    }
}
