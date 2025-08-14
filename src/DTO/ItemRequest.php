<?php

  namespace App\DTO;

  use Symfony\Component\Validator\Constraints as Assert;

  class ItemRequest
  {
      #[Assert\NotBlank]
      #[Assert\Length(max: 255)]
      public string $name;

      #[Assert\Choice(['fruit', 'vegetable'])]
      public string $type;

      #[Assert\Positive]
      public float $quantity;

      #[Assert\Choice(['g', 'kg'])]
      public string $unit;
  }