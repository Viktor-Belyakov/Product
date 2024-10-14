<?php

namespace App\Controller;

use App\Service\ElasticService;
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
     */
    #[Route('/product/search-by-description/', name: 'product_search_by_description')]
    public function searchByDescription(Request $request): JsonResponse
    {
        $description = $request->query->get('description');
        $additionalParams = $request->query->get('additionalParams');
        $result = $this->elasticService->findByDescription($description, $additionalParams);

        if ($result) {
            return new JsonResponse($result, Response::HTTP_OK, [], true);
        }

        return new JsonResponse([]);
    }
}
