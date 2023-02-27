<?php

declare(strict_types=1);

namespace App\Service\Collection;

interface CollectionInterface
{
    public function isInCollection(mixed $item) : bool;

    public function getByIndex(string|int $index) : mixed;

    public function isEmpty() : bool;
}
