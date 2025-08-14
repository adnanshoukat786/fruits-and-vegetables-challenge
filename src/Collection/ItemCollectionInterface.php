<?php

namespace App\Collection;

use App\Entity\Item;

interface ItemCollectionInterface
{
    public function add(Item $item): void;
    public function remove(int $id): bool;
    public function list(array $filters = []): array;
    public function search(string $query): array;
    public function count(): int;
    public function findById(int $id): ?Item;
    public function getType(): string;
}