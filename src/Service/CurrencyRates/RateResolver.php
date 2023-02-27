<?php

declare(strict_types=1);

namespace App\Service\CurrencyRates;

use App\Exceptions\RatesProviderException;
use App\Service\CurrencyRates\RatesProvider\RatesProviderInterface;

class RateResolver implements RateResolverInterface
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
