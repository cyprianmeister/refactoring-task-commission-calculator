<?php

declare(strict_types=1);

namespace App\Service\CurrencyRates;

interface RateResolverInterface
{
    public function resolve(string $targetCurrency, string $baseCurrency) : float;
}
