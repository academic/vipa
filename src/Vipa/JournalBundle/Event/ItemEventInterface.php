<?php

namespace Vipa\JournalBundle\Event;

use Symfony\Component\HttpFoundation\Response;

interface ItemEventInterface
{
    public function getItem();

    /**
     * @return Response
     */
    public function getResponse();

    /**
     * @param Response $response
     */
    public function setResponse(Response $response);
}
