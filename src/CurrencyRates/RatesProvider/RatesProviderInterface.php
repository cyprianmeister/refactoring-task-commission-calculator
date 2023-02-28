<?php

declare(strict_types=1);

namespace App\CurrencyRates\RatesProvider;

use App\Collection\CollectionInterface;
use App\Exception\RatesProviderException;

interface RatesProviderInterface
{
    /**
     * @throws RatesProviderException
     */
    public function provide(string $baseCurrency) : CollectionInterface;
}
