<?php
/**
 * Created by PhpStorm.
 * User: emreyilmaz
 * Date: 20.05.15
 * Time: 13:43
 */
namespace Ojs\ReportBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class ObjectCommonStat
 * @package Ojs\ReportBundle\Document
 *
 *   Günlük Makale inceleme sayıları - DailyReviewCount
 *   Kabul edilen makale sayısı - AcceptedArticleCount
 *   Red edilen makale sayısı - DeclinedArticleCount
 *   Revize beklenen makale sayısı - RevisedArticleCount
 *   Kullanıcı sayısı / dergiye göre - UserCount
 *   Okur sayısı / dergiye göre - ReaderCount
 *   Abone sayısı / dergiye göre - MemberCount
 *   Yayınlanmış sayılar - PublishedIssueCount
 * @MongoDb\Document(collection="analytics_common_stats")
 */
class ObjectCommonStat
{

    const DailyReviewCount = 1;
    const AcceptedArticleCount = 2;
    const DeclinedArticleCount = 3;
    const RevisedArticleCount = 4;
    const UserCount = 5;
    const ReaderCount = 6;
    const MemberCount = 7;
    const PublishedIssueCount = 8;
    const JournalViewCount = 9;
    const JournalDownloadCount = 10;

    /**
     * @var int
     * @MongoDb\Id
     *          MongoDB identifier id
     */
    public $id;
    /**
     * @var int
     * @MongoDB\Int
     *          Stat type as int. its configured as constant.
     */
    public $statType;

    /**
     * @var int
     * @MongoDB\Int
     *          Total count
     */
    public $count;

    /**
     * @var string
     * @MongoDB\String
     *             Full entity name like `Ojs\JournalBundle\Entity\Journal`
     */
    public $entity;

    /**
     * @var Int
     * @MongoDB\Int
     *          Object id in entity
     */
    public $object;

    /**
     * @var \DateTime
     * @MongoDB\Date
     *                Count date
     */
    public $date;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param  mixed            $id
     * @return ObjectCommonStat
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatType()
    {
        return $this->statType;
    }

    /**
     * @param  int              $statType
     * @return ObjectCommonStat
     */
    public function setStatType($statType)
    {
        $this->statType = $statType;

        return $this;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param  int              $count
     * @return ObjectCommonStat
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param  string           $entity
     * @return ObjectCommonStat
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @return Int
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param  Int              $object
     * @return ObjectCommonStat
     */
    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param  \DateTime $date
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }
}
