<?php

declare(strict_types=1);

namespace App\Service\Money;

interface MoneyConverterInterface
{
    public function convert(Money $money, string $targetCurrency) : Money;
}
