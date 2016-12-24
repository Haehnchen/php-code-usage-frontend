<?php
declare(strict_types = 1);

namespace espend\Inspector\ElasticsearchBundle\Importer;

use Elasticsearch\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\Finder\Finder;

class Importer
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var string
     */
    private $path;

    /**
     * Importer constructor.
     *
     * @param Client $client
     * @param string $path
     * @param LoggerInterface $logger
     */
    public function __construct(Client $client, string $path, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
        $this->path = $path;
    }

    /**
     * @param string $path
     */
    public function import()
    {
        $batch = [];
        $i = 0;

        foreach ($this->visitFile() as $json) {
            foreach($json['items'] as $item) {
                if (!isset($item['key'])) {
                    $this->logger->debug('skipping');
                    continue;
                }

                $batch[] = [
                    'index' => [
                        '_id' => md5($item['class'] . $item['key']),
                        '_index' => 'code-usage',
                        '_type' => 'class',
                    ]
                ];

                $batch[] = $item;

                if ($i++ % 100 == 0) {
                    $this->commit($batch);
                    $batch = [];
                }
            }
        };

        if (count($batch) > 0) {
            $this->commit($batch);
        }
    }

    /**
     * @return array|\Generator
     */
    private function visitFile()
    {
        foreach ((new Finder())->in($this->path)->name('*.php')->files() as $file) {
            yield json_decode($file->getContents(), true);
        };
    }

    /**
     * @param $batch
     */
    private function commit(array $batch)
    {
        $this->logger->info('Commit:' . count($batch) / 2);
        $response = $this->client->bulk(['body' => $batch]);
        $this->logger->debug(json_encode($response));
    }
}