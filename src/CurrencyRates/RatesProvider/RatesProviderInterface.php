<?php

declare(strict_types=1);

namespace App\CurrencyRates\RatesProvider;

use App\Collection\CollectionInterface;
use App\Exception\FetchRatesAdapterException;

interface RatesProviderInterface
{
    /**
     * @throws FetchRatesAdapterException
     */
    public function provide(string $baseCurrency) : CollectionInterface;
}
