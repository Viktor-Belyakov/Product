<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Elastic\Elasticsearch\Response\Elasticsearch;
use Symfony\Component\HttpFoundation\Response;

class ElasticService
{
    private string $indexName;
    private Client $client;

    /**
     * @param ProductRepository $productRepository
     * @param string $indexName
     * @throws AuthenticationException
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public function __construct(
        private readonly ProductRepository $productRepository,
        string $indexName = 'products'
    )
    {
        $this->client = ClientBuilder::create()
            ->setHosts([$_ENV['ELASTICSEARCH_HOST']])
            ->build();

        $this->indexName = $indexName;
        $this->createIndex();
    }

    /*********************************** PUBLIC METHOD **********************/
    /**
     * @return bool
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public function isConnected(): bool
    {
        return !is_null($this->client->info());
    }

    /**
     * @param array $data
     * @return bool
     * @throws ClientResponseException
     * @throws ServerResponseException
     * @throws MissingParameterException
     */
    public function addRecord(array $data): bool
    {
        // Избавляемся от дублей
        if ($this->isExists($data['product_sku'])) {
            return false;
        }

        $params = [
            'index' => $this->indexName,
            'body' => $data
        ];

        $this->client->index($params);
        return true;
    }

    /**
     * @param string $description
     * @param array|null $additionalParams
     * @return bool|string
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public function findByDescription(string $description, ?array $additionalParams): bool|string
    {
        $params = $this->getParams($this->indexName, 'detail_text', $description, $additionalParams);
        return $this->find($params);
    }

    /*********************************** PRIVATE METHOD **********************/
    /**
     * @param $params
     * @return bool|string
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    private function find($params): bool|string
    {
        $response = $this->client->search($params);
        if (empty($response['hits']['hits'])) {
            return false;
        }

        $result = $this->parseSearchResults($response);
        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param int $sku
     * @return bool
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    private function isExists(int $sku): bool
    {
        $params = $this->getParams($this->indexName, 'product_sku', $sku, ['fuzziness' => 0]);

        $response = $this->client->search($params);
        return !empty($response['hits']['hits']);
    }

    /**
     * @param Elasticsearch $response
     * @return array
     */
    private function parseSearchResults(Elasticsearch $response): array
    {
        $results = [];
        foreach ($response['hits']['hits'] as $hit) {
            $productSource = $hit['_source'];
            $sku = $productSource['product_sku'];
            $results = array_merge($results, $this->productRepository->getProductBySkuAsArray($sku));
        }

        return $results;
    }

    /**
     * @param string $indexName
     * @param string $field
     * @param string $query
     * @param array|null $additionalParams
     * @return array
     */
    private function getParams(string $indexName, string $field, string $query, ?array $additionalParams): array
    {
        $size = $additionalParams['size'] ?? 10000;
        $fuzziness = $additionalParams['fuzziness'] ?? 1;

        return [
            'index' => $indexName,
            'body' => [
                'query' => [
                    'match' => [
                        $field => [
                            'query' => $query,
                            'fuzziness' => $fuzziness
                        ]
                    ]
                ]
            ],
            'size' => $size
        ];
    }

    /**
     * @return void
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    private function createIndex(): void
    {
        // Проверяем, существует ли index в ElasticSearch, если нет создаем новый index
        $result = $this->client->indices()->exists(['index' => $this->indexName]);
        if ($result->getStatusCode() != Response::HTTP_NOT_FOUND) {
            return;
        }

        $this->client->indices()->create([
            'index' => $this->indexName,
            'body' => [
                'settings' => [
                    'number_of_shards' => 2,
                    'number_of_replicas' => 0
                ],
                'mappings' => [
                    'properties' => [
                        'id' => ['type' => 'long'],
                        'product_sku' => ['type' => 'text'],
                        'name' => ['type' => 'text'],
                        'price' => ['type' => 'text'],
                        'detail_text' => ['type' => 'text'],
                        'level_1' => ['type' => 'text'],
                        'level_2' => ['type' => 'text'],
                        'level_3' => ['type' => 'text'],
                    ]
                ]
            ]
        ]);
    }
}
