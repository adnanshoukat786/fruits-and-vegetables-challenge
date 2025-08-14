<?php

namespace App\Controller;

use App\Service\CollectionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CollectionController extends AbstractController
{
    public function __construct(private CollectionManager $collectionManager) {}

    #[Route('/api/collections/{type}', methods: ['GET'])]
    public function getCollection(string $type, Request $request): JsonResponse
    {
        try {
            $collection = $this->collectionManager->getCollection($type);
            $filters = $this->extractFilters($request);
            $items = $collection->list($filters);

            $unit = $request->query->get('unit', 'g');
            $result = array_map(fn($item) => $item->toArray($unit), $items);

            return $this->json([
                'type' => $type,
                'count' => count($result),
                'items' => array_values($result)
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/api/collections/{type}/search', methods: ['GET'])]
    public function searchCollection(string $type, Request $request): JsonResponse
    {
        try {
            $collection = $this->collectionManager->getCollection($type);
            $query = $request->query->get('q', '');
            $items = $collection->search($query);

            $unit = $request->query->get('unit', 'g');
            $result = array_map(fn($item) => $item->toArray($unit), $items);

            return $this->json([
                'type' => $type,
                'query' => $query,
                'count' => count($result),
                'items' => array_values($result)
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    private function extractFilters(Request $request): array
    {
        $filters = [];
        if ($name = $request->query->get('name')) $filters['name'] = $name;
        if ($min = $request->query->get('min_quantity')) $filters['min_quantity'] = (float) $min;
        if ($max = $request->query->get('max_quantity')) $filters['max_quantity'] = (float) $max;
        if ($unit = $request->query->get('unit')) $filters['unit'] = $unit;
        return $filters;
    }
}