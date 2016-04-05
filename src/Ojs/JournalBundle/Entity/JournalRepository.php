<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\Common\Cache\FileCache;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Ojs\CoreBundle\Params\JournalStatuses;
use Ojs\UserBundle\Entity\User;

class JournalRepository extends EntityRepository
{

    private $currentPage;
    private $count;
    private $filter = [];
    private $start;
    private $offset = 12;
    private $publisher = null;

    /**
     * @param $id
     * @param bool|true $useCache
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getById($id, $useCache = true) {
        $query = $this->createQueryBuilder('j')
            ->andWhere('j.id = :id')
            ->setParameter('id', $id)
            ->getQuery();
        $query->useResultCache($useCache);
        return $query->getSingleResult();
    }
    /**
     * @return null
     */
    public function getPublisher()
    {
        return empty($this->publisher) ? false : $this->publisher;
    }

    /**
     * @param  null $publisher
     * @return $this
     */
    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * @return integer
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param  mixed $offset
     * @return $this
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @return integer
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param  mixed $start
     * @return $this
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return array
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param  mixed $count
     * @return $this
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @param integer $currentPage
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
    }

    public function all()
    {
        $this->setCurrentPage($this->getOffset());

        $qb = $this->createQueryBuilder('j');
        $qb->select('count(j.id)')
            ->where(
                $qb->expr()->eq('j.status', ':status')
            )
            ->setParameter('status', 3);

        if (isset($this->getFilter()['subject'])) {
            $subjects = $this->getFilter()['subject'];
            foreach ($subjects as $key => $subject) {
                $qb
                    ->join('j.subjects', 's_'.$key, 'WITH', 's_'.$key.'.slug=:subject_'.$key)
                    ->setParameter('subject_'.$key, $subject);
            }
        }

        if (isset($this->getFilter()['publisher_type'])) {
            $publishers = $this->getFilter()['publisher_type'];
            foreach ($publishers as $key => $publisher) {
                $qb
                    ->join('j.publisher', 'i_' . $key)
                    ->join(
                        'i_' . $key . '.publisherType',
                        'it_'.$key,
                        'WITH',
                        'it_' . $key . '.slug=:publisher_type_slug_' . $key
                    )
                    ->setParameter('publisher_type_slug_' . $key, $publisher);
            }
        }
        if ($this->getPublisher()) {
            $qb
                ->join('j.publisher', 'inst', 'WITH', 'inst.slug=:publisher')
                ->setParameter('publisher', $this->getPublisher());
        }

        $this->setCount($qb->getQuery()->getSingleScalarResult());
        $qb
            ->select('j')
            ->setFirstResult($this->getStart())
            ->setMaxResults($this->getOffset());

        return $qb->getQuery()->getResult();
    }

    /**
     * Unban user
     * @param  User    $user
     * @param  Journal $journal
     * @return bool
     */
    public function removeBannedUser(User $user, Journal $journal)
    {
        try {
            $em = $this->getEntityManager();
            if (!$journal->getBannedUsers()->contains($user)) {
                return true;
            }

            $journal->removeBannedUser($user);
            $user->removeRestrictedJournal($journal);
            $em->persist($user);
            $em->persist($journal);

            $em->flush();

            return true;
        } catch (\Exception $q) {
            return false;
        }
    }

    /**
     * Check ban status
     * @param  User    $user
     * @param  Journal $journal
     * @return bool
     */
    public function checkUserPermit(User $user, Journal $journal)
    {
        return $journal->getBannedUsers()->contains($user) ? false : true;
    }

    /**
     * @param Journal $journal
     * @return array
     */
    public function getIssuesByYear(Journal $journal)
    {
        $years = [];
        foreach($journal->getIssues() as $issue){
            if($issue->isPublished()){
                $years[$issue->getYear()][] = $issue;
            }
        }
        ksort($years);
        return array_reverse($years, true);
    }

    /**
     * @param  array     $data
     * @return Journal[]
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

    /**
     * @param  User         $user
     * @return Journal|null
     */
    public function findOneByUser(User $user)
    {
        $query = $this->createQueryBuilder('j')
            ->join('j.journalUsers', 'journal_user')
            ->andWhere('journal_user.user = :user')
            ->setParameter('user', $user)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
        if ($query) {
            return $query[0];
        }

        return $query;
    }

    public function getHomePageList(FileCache $cache)
    {
        if(!$cache->contains('home_journal_offset')){
            $cache->save('home_journal_offset', rand(0, $this->getJournalCount()), 3600);
        }
        $cachedOffset = $cache->fetch('home_journal_offset');
        $list = $this->createQueryBuilder('j')
            ->select('partial j.{id,slug,issn,image,viewCount,downloadCount,founded}, partial i.{id,slug}')
            ->join('j.publisher', 'i')
            ->andWhere('j.status = :status')
            ->setParameter('status', JournalStatuses::STATUS_PUBLISHED)
            ->setFirstResult(rand(0, $this->getJournalCount()))
            ->setFirstResult($cachedOffset)
            ->setMaxResults(12)
            ->getQuery()
            ->useQueryCache(true)
            ->useResultCache(true, 3600)
            ->getResult(Query::HYDRATE_OBJECT)
        ;
        return $list;
    }

    /**
     * returns published
     */
    public function getJournalCount()
    {
        return $this->createQueryBuilder('j')
            ->select("count(j.id)")
            ->where("j.status = :status")
            ->setParameter('status', JournalStatuses::STATUS_PUBLISHED)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }
}
