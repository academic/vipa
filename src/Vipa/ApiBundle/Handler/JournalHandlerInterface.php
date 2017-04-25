<?php

namespace Vipa\ApiBundle\Handler;

use Vipa\JournalBundle\Entity\Journal;

interface JournalHandlerInterface
{
    /**
     * Get a Journal given the identifier
     *
     * @api
     *
     * @param mixed $id
     *
     * @return Journal
     */
    public function get($id);

    /**
     * Get a list of Journals.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array|Journal
     */
    public function all($limit = 5, $offset = 0);

    /**
     * Post Journal, creates a new Journal.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return Journal
     */
    public function post(array $parameters);

    /**
     * Edit a Journal.
     *
     * @api
     *
     * @param Journal   $entity
     * @param array           $parameters
     *
     * @return Journal
     */
    public function put(Journal $entity, array $parameters);

    /**
     * Partially update a Journal.
     *
     * @api
     *
     * @param Journal   $entity
     * @param array           $parameters
     *
     * @return Journal
     */
    public function patch(Journal $entity, array $parameters);

    /**
     * Delete a Journal.
     *
     * @api
     *
     * @param Journal   $entity
     *
     * @return Journal
     */
    public function delete(Journal $entity);
}