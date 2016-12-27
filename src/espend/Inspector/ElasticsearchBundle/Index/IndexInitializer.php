<?php
declare(strict_types = 1);

namespace espend\Inspector\ElasticsearchBundle\Index;

use Elasticsearch\Client;

class IndexInitializer
{
    /**
     * @var Client
     */
    private $client;

    /**
     * IndexInitializer constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function init()
    {
        $index = json_decode(file_get_contents(__DIR__ . '/index.json'), true);

        if ($this->client->indices()->exists(['index' => ['code-usage']])) {
            $this->client->indices()->delete([
                'index' => 'code-usage',
            ]);
        }

        $this->client->indices()->create([
            'index' => 'code-usage',
            'body' => $index,
        ]);
    }
}