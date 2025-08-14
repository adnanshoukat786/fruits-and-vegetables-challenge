<?php

namespace App\Tests\Unit;

use App\Collection\FruitCollection;
use App\Storage\InMemoryStorage;
use App\Entity\Item;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function testAddAndList(): void
    {
        $storage = new InMemoryStorage();
        $collection = new FruitCollection($storage);

        $item = new Item(1, 'Apple', 'fruit', 1000, 'g');
        $collection->add($item);

        $this->assertEquals(1, $collection->count());
        $items = $collection->list();
        $this->assertCount(1, $items);
        $this->assertEquals('Apple', array_values($items)[0]->name);
    }

    public function testSearch(): void
    {
        $storage = new InMemoryStorage();
        $collection = new FruitCollection($storage);

        $apple = new Item(1, 'Apple', 'fruit', 1000, 'g');
        $banana = new Item(2, 'Banana', 'fruit', 500, 'g');

        $collection->add($apple);
        $collection->add($banana);

        $results = $collection->search('apple');
        $this->assertCount(1, $results);
        $this->assertEquals('Apple', array_values($results)[0]->name);
    }
}