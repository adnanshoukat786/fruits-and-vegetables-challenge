<?php

  namespace App\Service;

  use App\Collection\FruitCollection;
  use App\Collection\VegetableCollection;
  use App\Collection\ItemCollectionInterface;
  use App\Entity\Item;
  use App\Storage\StorageInterface;

  class CollectionManager
  {
      private FruitCollection $fruitCollection;
      private VegetableCollection $vegetableCollection;

      public function __construct(StorageInterface $storage)
      {
          $this->fruitCollection = new FruitCollection($storage);
          $this->vegetableCollection = new VegetableCollection($storage);
      }

      public function getFruitCollection(): FruitCollection
      {
          return $this->fruitCollection;
      }

      public function getVegetableCollection(): VegetableCollection
      {
          return $this->vegetableCollection;
      }

      public function getCollection(string $type): ItemCollectionInterface
      {
          return match ($type) {
              'fruit', 'fruits' => $this->fruitCollection,
              'vegetable', 'vegetables' => $this->vegetableCollection,
              default => throw new \InvalidArgumentException("Invalid collection type: {$type}")
          };
      }

      public function addItem(Item $item): void
      {
          $collection = $this->getCollection($item->type);
          $collection->add($item);
      }

      public function getAllItems(): array
      {
          return [
              'fruits' => $this->fruitCollection->list(),
              'vegetables' => $this->vegetableCollection->list()
          ];
      }

      public function getTotalCount(): int
      {
          return $this->fruitCollection->count() + $this->vegetableCollection->count();
      }
  }
