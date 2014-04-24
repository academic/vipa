<?php

namespace Ojstr\JournalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContactTypes
 */
class ContactTypes extends TimestampableEntity {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var datetime $created 
     * @Gedmo\Timestampable(on="create")
     */
    private $created;

    /**
     * @var datetime $updated
     * @Gedmo\Timestampable
     */
    private $updated;

    /**
     * @var datetime $contentChanged
     * @Gedmo\Timestampable()
     */
    private $contentChanged;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ContactTypes
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return ContactTypes
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

}
