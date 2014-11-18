<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ojs\Common\Params\CommonParams;
use Symfony\Component\HttpFoundation\Request;

class JournalRepository extends EntityRepository
{
    private $currentPage;
    private $count;
    private $limit = 1;
    private $filter = [];

    /**
     * @return array
     */
    public function getFilter($key = null)
    {
        if (isset($this->filter[$key]))
            return $this->filter[$key];
        return $this->filter;
    }

    /**
     * @param array $filter
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param mixed $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return mixed
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @param mixed $currentPage
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param mixed $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function all($page)
    {
        $this->setCurrentPage($page - 1);
        $qb = $this->createQueryBuilder('j');
        $qb->select('count(j.id)')
            ->where(
                $qb->expr()->eq('j.status', ':status')
            )
            ->setParameter('status',3)
        ;


        if ($this->getFilter('subject')) {
            $qb
                ->join('j.subjects', 's', 'WITH', 's.id=:subject_id')
                ->setParameter('subject_id', $this->getFilter('subject'));
        }

        if ($this->getFilter('institution')) {
            $qb
                ->join('j.institution', 'i', 'WITH', 'i.id=:institution_id')
                ->setParameter('institution_id', (int)$this->getFilter('institution'));
        }

        $this->setCount($qb->getQuery()->getSingleScalarResult());
        $qb
            ->select('j')
            ->setFirstResult($this->getCurrentPage() * $this->getLimit())
            ->setMaxResults($this->getLimit());

        return $qb->getQuery()->getResult();
    }


    public function getTotalPageCount()
    {
        return ceil($this->getCount() / $this->getLimit());
    }


}
