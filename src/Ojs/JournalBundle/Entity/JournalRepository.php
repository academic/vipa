<?php

namespace Ojs\JournalBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class JournalRepository extends EntityRepository
{

    private $currentPage;
    private $count;
    private $filter = [];
    private $start;
    private $offset = 12;
    private $institution = null;

    /**
     * @return null
     */
    public function getInstitution()
    {
        return empty($this->institution) ? false : $this->institution;
    }

    /**
     * @param  null  $institution
     * @return $this
     */
    public function setInstitution($institution)
    {
        $this->institution = $institution;

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

    public function addFilter($key, $value)
    {
        $filter = $this->getFilter();
        if (isset($filter[$key])) {
            $filter[$key][] = $value;
        } else {
            $filter[$key] = [$value];
        }
        $this->filter = $filter;

        return $this;
    }

    /**
     * @param  Request $request
     * @return $this
     */
    public function setFilter(Request $request)
    {
        $filters = [];
        $filters['institution_type'] = $this->parseFilter($request->get('institution_type'));
        $filters['subject'] = $this->parseFilter($request->get('subject'));
        $this->filter = $filters;

        return $this;
    }

    /**
     * @param $filter
     * @return array|null
     */
    public function parseFilter($filter)
    {
        if (empty($filter)) {
            return;
        }

        return explode('|', $filter);
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

        if (isset($this->getFilter()['institution_type'])) {
            $institutions = $this->getFilter()['institution_type'];
            foreach ($institutions as $key => $institution) {
                $qb
                    ->join('j.institution', 'i_'.$key)
                    ->join(
                        'i_'.$key.'.institution_type',
                        'it_'.$key,
                        'WITH',
                        'it_'.$key.'.slug=:institution_type_slug_'.$key
                    )
                    ->setParameter('institution_type_slug_'.$key, $institution);
            }
        }
        if ($this->getInstitution()) {
            $qb
                ->join('j.institution', 'inst', 'WITH', 'inst.slug=:institution')
                ->setParameter('institution', $this->getInstitution());
        }

        $this->setCount($qb->getQuery()->getSingleScalarResult());
        $qb
            ->select('j')
            ->setFirstResult($this->getStart())
            ->setMaxResults($this->getOffset());

        return $qb->getQuery()->getResult();
    }

    public function getTotalPageCount()
    {
        return ceil($this->getCount() / $this->getOffset());
    }

    /**
     * Ban user
     * @param  User    $user
     * @param  Journal $journal
     * @return bool
     */
    public function banUser(User $user, Journal $journal)
    {
        try {
            $em = $this->getEntityManager();
            if ($journal->getBannedUsers()->contains($user)) {
                return true;
            }
            $journal->addBannedUser($user);
            $user->addRestrictedJournal($journal);
            $em->persist($journal);
            $em->persist($user);
            $em->flush();

            return true;
        } catch (\Exception $t) {
            echo $t->getMessage();

            return false;
        }
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
     * Just get journal's last issue id
     * @param  Journal $journal
     * @return integer
     */
    public function getLastIssueId($journal)
    {
        $q = $this->_em
            ->createQuery(
                'SELECT i FROM OjsJournalBundle:Issue i WHERE i.journalId =:j '
                .'AND i.datePublished IS NOT NULL ORDER BY i.datePublished DESC'
            )
            ->setMaxResults(1)
            ->setParameter('j', $journal->getId());
        try {
            $issue = $q->getOneOrNullResult();

            return $issue;
        } catch (NoResultException $e) {
            return false;
        }

        return false;
    }

    /**
     * @param string $institution
     */
    public function getByInstitutionAndSubject($institution, Subject $subject)
    {
        $qb = $this->createQueryBuilder('j');
        $qb
            ->join('j.institution', 'i', 'WITH', 'i.slug=:institution')
            ->join('j.subjects', 's', 'WITH', 's.id=:subject')
            ->setParameter('institution', $institution)
            ->setParameter('subject', $subject->getId());

        return $qb->getQuery()->getResult();
    }

    /**
     *  return all issues as array as grouped by year
     * @param  Journal $journal
     * @return array
     */
    public function getIssuesByYear(Journal $journal, $maxYearCount = 10)
    {
        $issues = $journal->getIssues();
        $years = [];
        /* @var $issue Issue */
        $count = 0;
        foreach ($issues as $issue) {
            if ($count++ > $maxYearCount) {
                break;
            }
            $year = $issue->getYear();
            $years[$year][] = $issue;
        }
        krsort($years);

        return $years;
    }

    /**
     *
     * @param  Journal $journal
     * @return array
     */
    public function getVolumes(Journal $journal)
    {
        $issues = $journal->getIssues();
        $volumes = [];
        foreach ($issues as $issue) {
            /* @var $issue Issue */
            $volume = $issue->getVolume();
            $volumes[$volume]['issues'][] = $issue;
            $volumes[$volume]['volume'] = $volume;
        }

        return $volumes;
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
     * @param  User      $user
     * @return Journal[]
     */
    public function findAllByUser(User $user)
    {
        $query = $this->createQueryBuilder('j')
            ->join('j.journalUsers', 'journal_user')
            ->andWhere('journal_user.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        return $query;
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

    /**
     * @return array
     */
    public function getAllTitles()
    {
        $result = $this->createQueryBuilder('journal')
            ->select('journal.title')->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);

        return $result;
    }

    public function getHomePageList()
    {
        $query = $this->createQueryBuilder('j')
            ->select('partial j.{id,slug,issn,title,image}, partial i.{id,slug}')
            ->join('j.institution', 'i')
            ->andWhere('j.status = :status')
            ->setParameter('status', 1)
            ->setMaxResults(12)->getQuery();

        return $query->useResultCache(true)->getResult(Query::HYDRATE_OBJECT);
    }
}
