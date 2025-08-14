<?php

namespace App\Service;

use App\Entity\Item;

class StorageService
{
    public function __construct(
        private CollectionManager $collectionManager,
        private string $projectDir
    ) {}

    public function processJsonFile(): void
    {
        $jsonContent = $this->getRequest();
        $data = json_decode($jsonContent, true);

        foreach ($data as $itemData) {
            $item = new Item(
                id: $itemData['id'],
                name: $itemData['name'],
                type: $itemData['type'],
                quantity: $itemData['quantity'],
                unit: $itemData['unit']
            );
            $this->collectionManager->addItem($item);
        }
    }

    public function getRequest(): string
    {
        return file_get_contents($this->projectDir . '/request.json');
    }
}