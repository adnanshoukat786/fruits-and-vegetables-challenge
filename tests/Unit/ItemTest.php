<?php

namespace App\Tests\Unit;

use App\Entity\Item;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public function testItemCreation(): void
    {
        $item = new Item(1, 'Apple', 'fruit', 5, 'kg');

        $this->assertEquals(1, $item->id);
        $this->assertEquals(5000, $item->quantityInGrams);
    }

    public function testUnitConversion(): void
    {
        $item = new Item(1, 'Carrot', 'vegetable', 2000, 'g');

        $this->assertEquals(2000, $item->getQuantityInUnit('g'));
        $this->assertEquals(2.0, $item->getQuantityInUnit('kg'));
    }

    public function testSearch(): void
    {
        $item = new Item(1, 'Red Apple', 'fruit', 1000, 'g');

        $this->assertTrue($item->matchesSearch('apple'));
        $this->assertFalse($item->matchesSearch('banana'));
    }
}