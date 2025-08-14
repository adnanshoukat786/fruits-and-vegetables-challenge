<?php

namespace App\Controller;

use App\Service\StorageService;
use App\Service\CollectionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DataController extends AbstractController
{
    #[Route('/load-data', methods: ['GET'])]
    public function loadData(StorageService $storageService, CollectionManager $manager): JsonResponse
    {
        try {
            $storageService->processJsonFile();

            return $this->json([
                'success' => true,
                'message' => 'Data loaded successfully',
                'data' => [
                    'fruits' => $manager->getFruitCollection()->count(),
                    'vegetables' => $manager->getVegetableCollection()->count(),
                    'total' => $manager->getTotalCount()
                ]
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}