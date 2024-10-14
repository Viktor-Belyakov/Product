<?php

namespace App\MessageHandler;

use App\Message\ProductMessage;
use App\Service\DataBaseService;
use App\Service\ElasticService;
use App\Service\ProductService;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Exception;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class ProductMessageHandler
{
    public function __construct(
        private ProductService $productService,
        private ElasticService $elasticService,
        private DataBaseService $dataBaseService,
    )
    {
    }

    /**
     * @throws ClientResponseException
     */
    public function __invoke(ProductMessage $productMessage): void
    {
        $data = $productMessage->getMessage();
        $associativeData = $this->productService->getAssociativeData($data);

        try {
            if ($this->dataBaseService->isConnected()) {
                $this->productService->createProduct($associativeData);
            }

            if ($this->elasticService->isConnected()) {
                $this->elasticService->addRecord($associativeData);
            }
        } catch (Exception $exception) {
            throw new ClientResponseException($exception->getMessage());
        }
    }
}
