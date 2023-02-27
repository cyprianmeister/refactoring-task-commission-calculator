<?php

declare(strict_types=1);

namespace App\Service\Collection;

class Collection implements CollectionInterface
{
    public function __construct(private readonly array $items)
    {
    }

    public function isInCollection(mixed $item) : bool
    {
        return \in_array($item, $this->items, true);
    }

    public function getByIndex(string|int $index) : mixed
    {
        return $this->items[$index] ?? null;
    }

    public function isEmpty() : bool
    {
        return empty($this->items);
    }
}
