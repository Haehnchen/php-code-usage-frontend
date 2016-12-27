<?php

namespace espend\Inspector\FrontendBundle\Repository;

use Elastica\Query;
use Elastica\Result;
use Elastica\SearchableInterface;
use espend\Inspector\CoreBundle\Entity\InspectorDynamic;
use espend\Inspector\CoreBundle\Entity\InspectorFile;
use espend\Inspector\CoreBundle\Entity\InspectorProject;

class UsageRepository
{
    /**
     * @var SearchableInterface
     */
    private $index;

    public function __construct(SearchableInterface $index)
    {
        $this->index = $index;
    }

    /**
     * @param string $class
     * @param string $type
     * @param int $offset
     * @param int $limit
     * @return InspectorDynamic[]
     */
    public function findUsage(string $class, string $type, int $offset = 0, int $limit = 25) : array
    {
        $query = new Query();

        $bool = new Query\BoolQuery();

        $bool->addMust((new Query\Term())->setTerm('class', $class));
        $bool->addMust((new Query\Term())->setTerm('type', $type));

        $query->setQuery($bool);
        $query->setSize($limit);
        $query->setFrom($offset);

        /** @var $result Result[] */
        if (count($results = $this->index->search($query)->getResults()) === 0) {
            return [];
        }

        return array_map(function (Result $result) {
            $data = $result->getData();
            dump($data);
            return (new InspectorDynamic())
                ->setContext($data['context'] ?? [])
                ->setKey($data['key'] ?? 'n/a')
                ->setName($data['name'] ?? 'n/a')
                ->setFile((new InspectorFile())->setProject(new InspectorProject()))
            ;
        }, $results);
    }

    /**
     * @param string $class
     * @param string $name
     * @param int $offset
     * @param int $limit
     * @return InspectorDynamic[]
     */
    public function findMethodUsage(string $class, string $name, int $offset = 0, int $limit = 25) : array
    {
        $query = new Query();

        $bool = new Query\BoolQuery();

        // @TODO: collect extends and class implementation
        $bool->addMust((new Query\Term())->setTerm('class', $class));
        $bool->addMust((new Query\Term())->setTerm('type', 'method'));
        $bool->addMust((new Query\Term())->setTerm('name', $name));

        $query->setQuery($bool);
        $query->setSize($limit);
        $query->setFrom($offset);

        /** @var $result Result[] */
        if (count($results = $this->index->search($query)->getResults()) === 0) {
            return [];
        }
dump($results);
        return array_map(function (Result $result) {
            $data = $result->getData();
            dump($data);
            return (new InspectorDynamic())
                ->setContext($data['context'] ?? [])
                ->setKey($data['key'] ?? 'n/a')
                ->setName($data['name'] ?? 'n/a')
                ->setFile((new InspectorFile())->setProject(new InspectorProject()));
        }, $results);
    }
}