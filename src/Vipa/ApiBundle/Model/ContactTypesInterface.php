<?php

namespace Vipa\ApiBundle\Model;

Interface ContactTypesInterface
{
    /**
     * Set title
     *
     * @param string $name
     * @return ContactTypesInterface
     */
    public function setName($name);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Set description
     *
     * @param string $description
     * @return ContactTypesInterface
     */
    public function setDescription($description);

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription();
}