<?php

namespace App\Tests\App\Service;

use App\Service\StorageService;
use App\Service\CollectionManager;
use App\Storage\InMemoryStorage;
use PHPUnit\Framework\TestCase;

class StorageServiceTest extends TestCase
{
    public function testProcessJsonFile(): void
    {
        $storage = new InMemoryStorage();
        $collectionManager = new CollectionManager($storage);
        $projectDir = __DIR__ . '/../../..';

        // Create service with correct constructor
        $storageService = new StorageService($collectionManager, $projectDir);

        // Test JSON processing
        $storageService->processJsonFile();

        // Assert data was loaded
        $this->assertGreaterThan(0, $collectionManager->getFruitCollection()->count());
        $this->assertGreaterThan(0, $collectionManager->getVegetableCollection()->count());
    }

    public function testGetRequest(): void
    {
        $storage = new InMemoryStorage();
        $collectionManager = new CollectionManager($storage);
        $projectDir = __DIR__ . '/../../..';

        $storageService = new StorageService($collectionManager, $projectDir);
        $request = $storageService->getRequest();

        $this->assertNotEmpty($request);
        $this->assertIsString($request);
        $this->assertJson($request);
    }
}