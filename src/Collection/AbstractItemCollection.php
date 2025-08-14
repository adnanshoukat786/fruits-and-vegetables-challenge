<?php

namespace App\Collection;

use App\Entity\Item;
use App\Storage\StorageInterface;

abstract class AbstractItemCollection implements ItemCollectionInterface
{
    public function __construct(
        protected StorageInterface $storage,
        protected string $type
    ) {}

    public function add(Item $item): void
    {
        if ($item->type !== $this->type) {
            throw new \InvalidArgumentException("Item must be of type: {$this->type}");
        }
        $this->storage->store($item);
    }

    public function remove(int $id): bool
    {
        return $this->storage->remove($id);
    }

    public function list(array $filters = []): array
    {
        $items = $this->storage->findByType($this->type);

        return $this->applyFilters($items, $filters);
    }

    public function search(string $query): array
    {
        return $this->storage->search($query, $this->type);
    }

    public function count(): int
    {
        return count($this->storage->findByType($this->type));
    }

    public function findById(int $id): ?Item
    {
        $item = $this->storage->findById($id);
        return $item && $item->type === $this->type ? $item : null;
    }

    public function getType(): string
    {
        return $this->type;
    }

    private function applyFilters(array $items, array $filters): array
    {
        if (empty($filters)) {
            return $items;
        }

        return array_filter($items, function (Item $item) use ($filters) {
            if (isset($filters['name']) && !$item->matchesSearch($filters['name'])) {
                return false;
            }

            if (isset($filters['min_quantity'])) {
                $unit = $filters['unit'] ?? 'g';
                if ($item->getQuantityInUnit($unit) < $filters['min_quantity']) {
                    return false;
                }
            }

            if (isset($filters['max_quantity'])) {
                $unit = $filters['unit'] ?? 'g';
                if ($item->getQuantityInUnit($unit) > $filters['max_quantity']) {
                    return false;
                }
            }

            return true;
        });
    }
}
