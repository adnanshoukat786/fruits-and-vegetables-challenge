<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Item
{
    #[Assert\Positive]
    public readonly int $id;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public readonly string $name;

    #[Assert\Choice(['fruit', 'vegetable'])]
    public readonly string $type;

    #[Assert\Positive]
    public readonly int $quantityInGrams;

    public function __construct(int $id, string $name, string $type, float $quantity, string $unit)
    {
        $this->id = $id;
        $this->name = trim($name);
        $this->type = strtolower($type);
        $this->quantityInGrams = (int) ($unit === 'kg' ? $quantity * 1000 : $quantity);
    }

    public function getQuantityInUnit(string $unit = 'g'): float
    {
        return $unit === 'kg' ? round($this->quantityInGrams / 1000, 3) : $this->quantityInGrams;
    }

    public function matchesSearch(string $term): bool
    {
        return str_contains(strtolower($this->name), strtolower(trim($term)));
    }

    public function toArray(string $unit = 'g'): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'quantity' => $this->getQuantityInUnit($unit),
            'unit' => $unit
        ];
    }
}