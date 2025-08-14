<?php

namespace App\Collection;

use App\Storage\StorageInterface;

class VegetableCollection extends AbstractItemCollection
{
    public function __construct(StorageInterface $storage)
    {
        parent::__construct($storage, 'vegetable');
    }
}