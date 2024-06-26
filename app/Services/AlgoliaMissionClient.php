<?php

namespace App\Services;

use Algolia\AlgoliaSearch\SearchClient;

class AlgoliaMissionClient
{
    public SearchClient $client;
    public string $indexName;
    public $index;

    public function __construct()
    {
        $this->client = SearchClient::create(config('services.algolia.app_id'), config('services.algolia.secret'));
        $this->indexName = config('scout.prefix') . '_covid_missions';
        $this->index = $this->client->initIndex($this->indexName);
    }

    public function search($query = '', $options = [])
    {
        return $this->index->search($query, $options);
    }

    public function searchForFacetValues(string $facetName, string $facetQuery = '', $options = [])
    {
        return $this->index->searchForFacetValues($facetName, $facetQuery, $options);
    }

}
