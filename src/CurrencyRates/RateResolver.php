<?php

declare(strict_types=1);

namespace App\CurrencyRates;

use App\CurrencyRates\RatesProvider\RatesProviderInterface;
use App\Exception\RatesProviderException;

final class RateResolver implements RateResolverInterface
{
    public function __construct(private readonly RatesProviderInterface $ratesProvider)
    {
    }

    /**
     * @throws RatesProviderException
     */
    public function resolve(string $targetCurrency, string $baseCurrency) : float
    {
        return $this->ratesProvider->provide($baseCurrency)->getByIndex($targetCurrency);
    }
}
