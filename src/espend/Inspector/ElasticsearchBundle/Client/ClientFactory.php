<?php
declare(strict_types = 1);

namespace espend\Inspector\ElasticsearchBundle\Client;

use Elastica\Client as ElasticaClient;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class ClientFactory
{
    /**
     * @param string $host
     * @return Client
     */
    public static function create(string $host = 'localhost:9200') : Client
    {
        return ClientBuilder::create()->setHosts([$host])->build();
    }

    /**
     * @param string $host
     * @return ElasticaClient
     */
    public static function createElasticaClient(string $host = 'localhost:9200'): ElasticaClient
    {
        return new ElasticaClient(array(
            'host' => 'localhost',
            'port' => 9200,
        ));
    }
}