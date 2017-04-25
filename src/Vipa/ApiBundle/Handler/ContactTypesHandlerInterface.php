<?php

namespace Vipa\ApiBundle\Handler;

use Vipa\ApiBundle\Model\ContactTypesInterface;

interface ContactTypesHandlerInterface
{
    /**
     * Get a Contact Type given the identifier
     *
     * @api
     *
     * @param mixed $id
     *
     * @return ContactTypesInterface
     */
    public function get($id);

    /**
     * Get a list of Contact Types.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0);

    /**
     * Post Contact Type, creates a new Contact Type.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return ContactTypesInterface
     */
    public function post(array $parameters);

    /**
     * Edit a Contact Type.
     *
     * @api
     *
     * @param ContactTypesInterface   $contactType
     * @param array           $parameters
     *
     * @return ContactTypesInterface
     */
    public function put(ContactTypesInterface $contactType, array $parameters);

    /**
     * Partially update a Contact Type.
     *
     * @api
     *
     * @param ContactTypesInterface   $contactType
     * @param array           $parameters
     *
     * @return ContactTypesInterface
     */
    public function patch(ContactTypesInterface $contactType, array $parameters);

    /**
     * Delete a Contact Type.
     *
     * @api
     *
     * @param ContactTypesInterface   $entity
     *
     * @return ContactTypesInterface
     */
    public function delete(ContactTypesInterface $entity);
}