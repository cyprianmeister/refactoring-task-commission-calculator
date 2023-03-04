<?php

declare(strict_types=1);

namespace App\CurrencyRates\FetchRatesAdapter;

use App\Exception\FetchRatesAdapterException;

interface FetchRatesAdapterInterface
{
    /**
     * @throws FetchRatesAdapterException
     */
    public function fetchRates(string $baseCurrency) : array;
}
