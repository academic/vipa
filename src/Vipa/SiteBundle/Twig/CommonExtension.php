<?php
namespace Vipa\SiteBundle\Twig;

use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;
use Vipa\UserBundle\Entity\User;

class CommonExtension extends \Twig_Extension
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var FilterManager
     */
    private $filterManager;

    /**
     * @param EntityManagerInterface $entityManager
     * @param FilterManager          $filterManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FilterManager $filterManager
    ) {
        $this->em = $entityManager;
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
            $user = $this->em->getRepository('VipaUserBundle:User')->find($id);
        } else {
            /** @var User $user */
            $user = $this->em->getRepository('VipaUserBundle:User')->findOneBy(array('username' => $id));
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
