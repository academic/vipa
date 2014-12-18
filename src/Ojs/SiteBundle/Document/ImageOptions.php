<?php
/**
 * Date: 16.12.14
 * Time: 22:13
 */

namespace Ojs\SiteBundle\Document;


class ImageOptions
{

    /**
     * @var \MongoId $id
     */
    protected $id;

    /**
     * @var integer $height
     */
    protected $height;

    /**
     * @var integer $width
     */
    protected $width;

    /**
     * @var integer $x
     */
    protected $x;

    /**
     * @var integer $y
     */
    protected $y;

    /**
     * @var string $object
     */
    protected $object;

    /**
     * @var integer $object_id
     */
    protected $object_id;

    /**
     * @var string $image_type
     */
    protected $image_type;


    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set height
     *
     * @param integer $height
     * @return self
     */
    public function setHeight($height)
    {
        $this->height = (int)$height;
        return $this;
    }

    /**
     * Get height
     *
     * @return integer $height
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set width
     *
     * @param integer $width
     * @return self
     */
    public function setWidth($width)
    {
        $this->width = (int)$width;
        return $this;
    }

    /**
     * Get width
     *
     * @return integer $width
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set x
     *
     * @param integer $x
     * @return self
     */
    public function setX($x)
    {
        $this->x = (int)$x;
        return $this;
    }

    /**
     * Get x
     *
     * @return integer $x
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * Set y
     *
     * @param integer $y
     * @return self
     */
    public function setY($y)
    {
        $this->y = (int)$y;
        return $this;
    }

    /**
     * Get y
     *
     * @return integer $y
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * Set object
     *
     * @param string $object
     * @return self
     */
    public function setObject($object)
    {
        $this->object = $object;
        return $this;
    }

    /**
     * Get object
     *
     * @return string $object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set objectId
     *
     * @param integer $objectId
     * @return self
     */
    public function setObjectId($objectId)
    {
        $this->object_id = $objectId;
        return $this;
    }

    /**
     * Get objectId
     *
     * @return integer $objectId
     */
    public function getObjectId()
    {
        return $this->object_id;
    }

    /**
     * Set imageType
     *
     * @param string $imageType
     * @return self
     */
    public function setImageType($imageType)
    {
        $this->image_type = $imageType;
        return $this;
    }

    /**
     * Get imageType
     *
     * @return string $imageType
     */
    public function getImageType()
    {
        return $this->image_type;
    }

    public function init($data, $entity, $type)
    {
        $this->setHeight($data['height']);
        $this->setWidth($data['width']);
        $this->setX($data['x']);
        $this->setY($data['y']);
        $this->setObject(get_class($entity));
        $this->setObjectId(call_user_func([$entity, 'getId']));
        $this->setImageType($type);
        return $this;
    }
}
