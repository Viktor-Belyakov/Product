<?php

namespace App\Controller;

use App\Service\ElasticService;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product')]
class ProductController extends AbstractController
{
    public function __construct(
        private readonly ElasticService $elasticService,
    )
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    #[Route('/search-by-description', name: 'product_search_by_description', methods: ['GET'])]
    public function searchByDescription(Request $request): JsonResponse
    {
        $description = $request->query->get('description');
        $additionalParams = $request->query->get('additionalParams');
        $result = $this->elasticService->findByDescription($description, $additionalParams);

        return $result
            ? $this->json($result)
            : $this->json([]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    #[Route('/search-by-name', name: 'product_search_by_name', methods: ['GET'])]
    public function searchByName(Request $request): JsonResponse
    {
        $name = $request->query->get('name');
        $additionalParams = $request->query->get('additionalParams');
        $result = $this->elasticService->findByName($name, $additionalParams);

        return $result
            ? $this->json($result)
            : $this->json([]);
    }
}
