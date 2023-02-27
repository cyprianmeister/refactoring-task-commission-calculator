<?php

declare(strict_types=1);

namespace App\Service\Money;

use App\Service\CurrencyRates\RateResolverInterface;

class MoneyConverter implements MoneyConverterInterface
{
    public function __construct(
        private readonly RateResolverInterface $rateResolver
    ) {
    }

    public function convert(Money $money, string $targetCurrency) : Money
    {
        $rate = $this->rateResolver->resolve($money->getCurrency(), $targetCurrency);

        $convertedAmount = $money->getAmount() / $rate;

        return Money::create($convertedAmount, $targetCurrency);
    }
}
