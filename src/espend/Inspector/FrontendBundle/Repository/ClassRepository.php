<?php

namespace espend\Inspector\FrontendBundle\Repository;

use Elastica\Aggregation\Filter;
use Elastica\Aggregation\Terms;
use Elastica\Query;
use Elastica\Result;
use Elastica\SearchableInterface;
use espend\Inspector\CoreBundle\Entity\InspectorClass;
use espend\Inspector\FrontendBundle\Model\ClassAggregationResult;
use espend\Inspector\FrontendBundle\Model\FacetValue;

class ClassRepository
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
     * @return InspectorClass|null
     */
    public function findByClass(string $class) : ?InspectorClass
    {
        $query = new Query();

        $bool = new Query\BoolQuery();

        $bool->addMust((new Query\Term())->setTerm('class', $class));
        $bool->addMust((new Query\Term())->setTerm('type', 'class'));

        $query->setQuery($bool);
        $query->setSize(1);

        /** @var $result Result[] */
        if (count($results = $this->index->search($query)->getResults()) === 0) {
            return null;
        }

        $result = $results[0]->getSource();

        return (new InspectorClass())
            ->setClass($result['class'])
            ->setDocComment($result['doc_comment'] ?? null)
            ;
    }

    /**
     * @param string $class
     * @return ClassAggregationResult
     */
    public function findClassUsages(string $class) : ClassAggregationResult
    {
        $query = new Query();

        $bool = new Query\BoolQuery();
        $bool->addMust((new Query\Term())->setTerm('class', $class));

        $query->setQuery($bool);
        $query->setSize(1);

        $termsAgg = new Terms('types');
        $termsAgg->setField('type');
        $termsAgg->setSize(20);

        $filterAgg = new Filter('methods');
        $filterAgg->setFilter((new \Elastica\Filter\Term())->setTerm('type', 'method'));
        $filterAgg->addAggregation((new Terms('names'))->setField('name'));

        $query = new Query();
        $bool = new Query\BoolQuery();
        $bool->addMust((new Query\Term())->setTerm('class', $class));
        $query->setQuery($bool);

        $query->addAggregation($termsAgg);
        $query->addAggregation($filterAgg);
        $query->setSize(0);

        $aggs = $this->index->search($query)->getAggregations();

        $methods = array_map(function (array $value) {
            return new FacetValue($value['key'], $value['doc_count']);
        }, $aggs['methods']['names']['buckets'] ?? []);

        $types = array_map(function (array $value) {
            return new FacetValue($value['key'], $value['doc_count']);
        }, $aggs['types']['buckets'] ?? []);

        return new ClassAggregationResult($types, $methods);
    }

    /**
     * @param string $class
     * @return InspectorClass[]
     */
    public function findSubClasses(string $class) : array
    {
        $query = new Query();

        $bool = new Query\BoolQuery();

        $bool->addMust((new Query\Term())->setTerm('child', $class));

        $query->setQuery($bool);
        $query->setSize(30);

        /** @var $result Result[] */
        if (count($results = $this->index->search($query)->getResults()) === 0) {
            return [];
        }

        return array_map(function (Result $result) {
            $data = $result->getData();

            return (new InspectorClass())
                ->setClass($data['class']);
        }, $results);
    }
}