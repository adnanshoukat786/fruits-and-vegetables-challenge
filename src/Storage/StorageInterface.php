<?php

namespace App\Storage;

use App\Entity\Item;

interface StorageInterface
{
    public function store(Item $item): void;
    public function findById(int $id): ?Item;
    public function findByType(string $type): array;
    public function findAll(): array;
    public function remove(int $id): bool;
    public function search(string $query, ?string $type = null): array;
}
