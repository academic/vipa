<?php
namespace Ojs\JournalBundle\Document;


use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Class TransferredRecord
 * @package Ojs\JournalBundle\Document
 * @MongoDB\Document(collection="transferred_records")
 */
class TransferredRecord
{
    /**
     * @MongoDB\Id
     * @var integer
     */
    protected $id;

    /**
     * @MongoDB\Int
     * @var integer
     */
    protected $old_id;

    /**
     * @MongoDB\Int
     * @var integer
     */
    protected $new_id;


    /**
     * @MongoDB\String
     * @var string
     */
    protected $entity;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getNewId()
    {
        return $this->new_id;
    }

    /**
     * @param int $new_id
     * @return $this
     */
    public function setNewId($new_id)
    {
        $this->new_id = $new_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getOldId()
    {
        return $this->old_id;
    }

    /**
     * @param int $old_id
     * @return $this
     */
    public function setOldId($old_id)
    {
        $this->old_id = $old_id;
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
     * @param string $entity
     * @return $this
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }



}