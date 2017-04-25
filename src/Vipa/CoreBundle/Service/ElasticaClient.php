<?php
namespace Vipa\CoreBundle\Service;

use Elastica\Exception\ExceptionInterface;
use Elastica\Request;
use Elastica\Response;
use FOS\ElasticaBundle\Elastica\Client as BaseClient;

class ElasticaClient extends BaseClient
{
    public function request($path, $method = Request::GET, $data = array(), array $query = array())
    {
        try {
            return parent::request($path, $method, $data, $query);
        } catch (ExceptionInterface $e) {
            if ($this->_logger) {
                $this->_logger->warning('Failed to send a request to ElasticSearch', array(
                    'exception' => $e->getMessage(),
                    'path' => $path,
                    'method' => $method,
                    'data' => $data,
                    'query' => $query
                ));
            }

            return new Response('{"took":0,"timed_out":false,"hits":{"total":0,"max_score":0,"hits":[]}}');
        }
    }
}
