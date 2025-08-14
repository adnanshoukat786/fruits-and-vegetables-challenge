<?php

namespace App\Storage;

use App\Entity\Item;

class InMemoryStorage implements StorageInterface
{
    private array $items = [];

    public function store(Item $item): void
    {
        $this->items[$item->id] = $item;
    }

    public function findById(int $id): ?Item
    {
        return $this->items[$id] ?? null;
    }

    public function findByType(string $type): array
    {
        return array_filter($this->items, fn(Item $item) => $item->type === $type);
    }

    public function findAll(): array
    {
        return array_values($this->items);
    }

    public function remove(int $id): bool
    {
        if (isset($this->items[$id])) {
            unset($this->items[$id]);
            return true;
        }
        return false;
    }

    public function search(string $query, ?string $type = null): array
    {
        $items = $type ? $this->findByType($type) : $this->findAll();

        if (empty(trim($query))) {
            return $items;
        }

        return array_filter($items, fn(Item $item) => $item->matchesSearch($query));
    }
}
