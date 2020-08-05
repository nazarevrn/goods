<?php

namespace App\Services;

use App\Goods;
use App\Http\Requests\SearchRequest;
use Illuminate\Database\Eloquent\Builder;

class SearchGood
{
    //search params
    const SEARCH_FIELDS = [
        ['name' => 'category_id', 'operator' => '=', 'relation' => true,
            'column_name' => 'id'],
        ['name' => 'categoryName', 'operator' => 'LIKE',  'relation' => true,
            'column_name' => 'name'],
        ['name' => 'name', 'operator' => 'LIKE', 'relation' => false,
            'column_name' => 'goods.name'],
        ['name' => 'price_from',   'operator' => '>=',     'relation' => false,
            'column_name' => 'goods.price'],
        ['name' => 'price_to', 'operator' => '<=', 'relation' => false,
            'column_name' => 'goods.price'],
        ['name' => 'is_published', 'operator' => '=', 'relation' => false,
            'column_name' => 'goods.is_published'],
        ['name' => 'is_active', 'operator' => 'IS', 'relation' => false,
            'goods.deleted_at'],
    ];

    //variables to store search params values from request
    private $category_id, $categoryName, $name, $price_from, $price_to,
        $is_published, $is_active;

    private $searchRequest, $currentSearchValue, $whereConditions;

    public function __construct(SearchRequest $request)
    {
        $this->extractParams($request);
    }

    /**
     * Extract search params to local properties
     * @param SearchRequest $request
     */
    private function extractParams(SearchRequest $request)
    {
        foreach (self::SEARCH_FIELDS as $oneSearchFieldParams) {
            $searchParamName = $oneSearchFieldParams['name'];
            $searchParamValue = $request->get($searchParamName);
            if (!is_null($searchParamValue)) {
                $this->$searchParamName = $searchParamValue;
            }
        }
    }

    /**
     * Builds and makes search query
     *
     * @return mixed
     */
    public function make()
    {
        $this->buildQuery();
        $this->prepareQuery();
        return $this->makeQuery();
    }

    /**
     * Build search query
     */
    private function buildQuery()
    {
        foreach (self::SEARCH_FIELDS as $oneSearchFieldParams) {
            $fieldName = $oneSearchFieldParams['name'];
            $this->currentSearchValue = $this->$fieldName;
            if (!is_null($this->currentSearchValue)) {
                if ($oneSearchFieldParams['relation'] === true) {
                    $this->buildRelationQuery($oneSearchFieldParams);
                } else {
                    $this->buildWhereCondition($oneSearchFieldParams);
                }
            }
        }

        if (!empty($this->whereConditions)) {
            if (!empty($this->searchRequest)) {
                $this->searchRequest->where([$this->whereConditions]);
            } else {
                $this->searchRequest = Goods::where($this->whereConditions);
            }
        }

    }

    /**
     * Build query to search in relation table
     * @param array $searchFieldParams
     *
     */
    private function buildRelationQuery(array $searchFieldParams)
    {
        $searchValue = $this->currentSearchValue;
        if (empty($this->searchRequest)) {
            $this->searchRequest = Goods::whereHas('categories',
                function (Builder $query) use ($searchFieldParams,
                    $searchValue) {

                    if ($searchFieldParams['operator'] === 'LIKE') {
                        $searchValue = $this->getValueForLIKE($searchValue);
                    }

                    $query->where($searchFieldParams['column_name'],
                        $searchFieldParams['operator'],
                        $searchValue);
            });
        } else {
            //TODO add abstract method
            $this->searchRequest = $this->searchRequest->whereHas('categories',
                function (Builder $query) use ($searchFieldParams,
                    $searchValue) {

                    if ($searchFieldParams['operator'] === 'LIKE') {
                        $searchValue = $this->getValueForLIKE($searchValue);
                    }

                    $query->where($searchFieldParams['column_name'],
                        $searchFieldParams['operator'],
                        $searchValue);
                });
        }
    }

    /**
     * Add necessary symbols for LIKE value
     * @param string $value
     * @return string
     */
    private function getValueForLIKE(string $value)
    {
        return "%{$value}%";
    }

    /**
     * Builds single where condition
     * @param array $searchFieldParams
     */
    private function buildWhereCondition(array $searchFieldParams)
    {

        if ($searchFieldParams['operator'] !== 'IS') {

            if ($searchFieldParams['operator'] === 'LIKE') {
                $this->currentSearchValue = $this->getValueForLIKE(
                    $this->currentSearchValue);
            }

            $this->whereConditions[] = [
                $searchFieldParams['column_name'],
                $searchFieldParams['operator'],
                $this->currentSearchValue];

        } else {
            if ($this->currentSearchValue === '0') {
                if(!empty($this->searchRequest)) {
                    $this->searchRequest->whereNotNull('goods.deleted_at');
                } else {
                    $this->searchRequest = Goods::onlyTrashed();
                }
            }
        }

    }

    /**
     * Add generated where conditions
     */
    private function prepareQuery()
    {
        if (!empty($this->whereConditions)) {
            if (!empty($this->searchRequest)) {
                $this->searchRequest->where($this->whereConditions);
            } else {
                $this->searchRequest = Goods::where($this->whereConditions);
            }
        }
    }

    /**
     * Run generated query
     * @return mixed
     */
    private function makeQuery()
    {
        if (!empty($this->searchRequest)) {
            return $this->searchRequest->get();
        }
    }

}
