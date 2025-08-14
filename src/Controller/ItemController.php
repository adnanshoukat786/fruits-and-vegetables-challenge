<?php

namespace App\Controller;

use App\DTO\ItemRequest;
use App\Entity\Item;
use App\Service\CollectionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ItemController extends AbstractController
{
    public function __construct(
        private CollectionManager $collectionManager,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) {}

    #[Route('/api/items', methods: ['POST'])]
    public function addItem(Request $request): JsonResponse
    {
        try {
            $itemRequest = $this->serializer->deserialize(
                $request->getContent(),
                ItemRequest::class,
                'json'
            );

            $violations = $this->validator->validate($itemRequest);
            if (count($violations) > 0) {
                $errors = array_map(fn($v) => $v->getMessage(), iterator_to_array($violations));
                return $this->json(['errors' => $errors], 400);
            }

            $newId = $this->generateNewId();
            $item = new Item($newId, $itemRequest->name, $itemRequest->type, $itemRequest->quantity,
$itemRequest->unit);

            $this->collectionManager->addItem($item);

            return $this->json([
                'message' => 'Item added successfully',
                'item' => $item->toArray()
            ], 201);

        } catch (\Exception $e) {
            return $this->json(['error' => 'Failed to add item'], 400);
        }
    }

    #[Route('/api/items/{id}', methods: ['DELETE'])]
    public function removeItem(int $id): JsonResponse
    {
        try {
            $fruitRemoved = $this->collectionManager->getFruitCollection()->remove($id);
            $vegetableRemoved = $this->collectionManager->getVegetableCollection()->remove($id);
            
            if ($fruitRemoved || $vegetableRemoved) {
                return $this->json(['message' => 'Item removed successfully'], 200);
            }
            
            return $this->json(['error' => 'Item not found'], 404);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Failed to remove item'], 400);
        }
    }

    private function generateNewId(): int
    {
        $allItems = $this->collectionManager->getAllItems();
        $maxId = 0;

        foreach ([...$allItems['fruits'], ...$allItems['vegetables']] as $item) {
            $maxId = max($maxId, $item->id);
        }

        return $maxId + 1;
    }
}