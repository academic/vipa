<?php

namespace Vipa\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

trait FindTrait
{
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        /** @var EntityRepository $this */
        $query = $this->createQueryBuilder('s');

        $query->setMaxResults(1);
        foreach ($criteria as $key => $item) {
            $query->andWhere('s.'.$key.' = :'.$key);
            $query->setParameter($key, $item);
        }
        if ($orderBy) {
            $query->orderBy('s.'.$orderBy);
        }
        $query->setCacheable(true);

        return $query->getQuery()->getOneOrNullResult();
    }
}
