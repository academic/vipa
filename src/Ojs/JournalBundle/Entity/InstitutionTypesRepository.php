<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class InstitutionTypesRepository
 * @package Ojs\JournalBundle\Entity
 */
class InstitutionTypesRepository extends EntityRepository
{

    /**
     * @param array $data
     * @return mixed
     */
    public function getByIds(array $data)
    {
        $qb = $this->createQueryBuilder('j');
        $qb->where(
            $qb->expr()->in('j.id', ':data')
        )
            ->setParameter('data', $data);
        return $qb->getQuery()->getResult();
    }

}
