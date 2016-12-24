<?php
declare(strict_types = 1);

namespace espend\Inspector\ElasticsearchBundle\Client;

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
}