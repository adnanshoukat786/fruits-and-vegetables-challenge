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

    public function testRemoveItem(): void
    {
        $storage = new InMemoryStorage();
        $collection = new FruitCollection($storage);

        $apple = new Item(1, 'Apple', 'fruit', 500, 'g');
        $banana = new Item(2, 'Banana', 'fruit', 300, 'g');
        
        $collection->add($apple);
        $collection->add($banana);
        
        $this->assertCount(2, $collection->list());
        
        $removed = $collection->remove(1);
        $this->assertTrue($removed);
        
        $this->assertCount(1, $collection->list());
        
        $notRemoved = $collection->remove(999);
        $this->assertFalse($notRemoved);
    }
}