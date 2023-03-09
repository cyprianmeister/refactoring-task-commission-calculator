<?php

declare(strict_types=1);

namespace App\CurrencyRates;

use App\CurrencyRates\RatesProvider\RatesProviderInterface;

final class RateResolver implements RateResolverInterface
{
    public function __construct(private readonly RatesProviderInterface $ratesProvider)
    {
    }

    public function resolve(string $targetCurrency, string $baseCurrency) : float
    {
        return $this->ratesProvider->provide($baseCurrency)->getByIndex($targetCurrency);
    }
}
