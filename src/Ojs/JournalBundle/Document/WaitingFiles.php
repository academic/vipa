<?php
namespace Ojs\JournalBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class WaitingFiles
 * @package Ojs\JournalBundle\Document
 * @MongoDB\Document(collection="waiting_files")
 */
class WaitingFiles
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
    protected $url;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $path;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param  int   $id
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
     * @param  int   $new_id
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
     * @param  int   $old_id
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
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }


}
