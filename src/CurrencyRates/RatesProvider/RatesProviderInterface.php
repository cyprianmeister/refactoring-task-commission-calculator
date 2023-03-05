<?php

declare(strict_types=1);

namespace App\CurrencyRates\RatesProvider;

use App\Collection\CollectionInterface;

interface RatesProviderInterface
{
    public function provide(string $baseCurrency) : CollectionInterface;
}
