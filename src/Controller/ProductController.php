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
    #[Route('/product/search-by-description/', name: 'product_search_by_description')]
    public function searchByDescription(Request $request): JsonResponse
    {
        $params = $this->getQueryParams($request, 'description');
        $result = $this->elasticService->findByDescription($params['description'], $params['additionalParams']);

        if ($result) {
            return new JsonResponse($result, Response::HTTP_OK, [], true);
        }

        return new JsonResponse([]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    #[Route('/product/search-by-name/', name: 'product_search_by_name')]
    public function searchByName(Request $request): JsonResponse
    {
        $params = $this->getQueryParams($request, 'name');
        $result = $this->elasticService->findByName($params['name'], $params['additionalParams']);

        if ($result) {
            return new JsonResponse($result, Response::HTTP_OK, [], true);
        }

        return new JsonResponse([]);
    }

    private function getQueryParams(Request $request, string $key): array
    {
        return [
            $key => $request->query->get($key),
            'additionalParams' => $request->query->get('additionalParams')
        ];
    }
}
