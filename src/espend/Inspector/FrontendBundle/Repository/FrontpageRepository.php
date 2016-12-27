<?php

namespace espend\Inspector\FrontendBundle\Repository;

use Elastica\Aggregation\Terms;
use Elastica\Query;
use Elastica\SearchableInterface;
use espend\Inspector\CoreBundle\espendInspectorCoreEnum;

class FrontpageRepository
{
    /**
     * @var SearchableInterface
     */
    private $index;

    public function __construct(SearchableInterface $index)
    {
        $this->index = $index;
    }

    public function getTopAuthors(int $limit = 8)
    {
        $termsAgg = new Terms('authors');
        $termsAgg->setField('author.name');
        $termsAgg->setSize($limit);

        $query = new Query();
        $query->addAggregation($termsAgg);

        $aggregation = $this->index->search($query)->getAggregation('authors');

        return array_map(function(array $term) {
            return [
                'name' => $term['key'],
                'total' => $term['doc_count'],
            ];
        }, $aggregation['buckets'] ?? []);
    }

    public function getTopClasses(int $limit = 8)
    {
        $termsAgg = new Terms('class');
        $termsAgg->setField('class');
        $termsAgg->setSize($limit);

        $query = new Query();

        $bool = new Query\BoolQuery();
        $bool->addMustNot(new Query\Terms('class', espendInspectorCoreEnum::CORE_CLASS_REDUCE));

        $query->setQuery($bool);
        $query->addAggregation($termsAgg);

        $aggregation = $this->index->search($query)->getAggregation('class');

        return array_map(function (array $term) {
            return [
                'class' => $term['key'],
                'total' => $term['doc_count'],
            ];
        }, $aggregation['buckets'] ?? []);
    }
}