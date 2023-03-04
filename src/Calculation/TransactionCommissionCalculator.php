<?php

declare(strict_types=1);

namespace App\Calculation;

use App\Calculation\BinMultiplier\BinMultiplierResolverInterface;
use App\Money\Money;
use App\Money\MoneyConverterInterface;
use App\Transaction\Transaction;

final class TransactionCommissionCalculator implements TransactionCalculatorInterface
{
    public function __construct(
        private readonly MoneyConverterInterface $moneyConverter,
        private readonly BinMultiplierResolverInterface $multiplierResolver,
        private readonly string $targetCurrency
    ) {
    }

    public function calculate(Transaction $transaction) : float|int
    {
        $money = $this->moneyConverter->convert(
            Money::create($transaction->getAmount(), $transaction->getCurrency()),
            $this->targetCurrency
        );

        $commission = $money->getAmount() * $this->multiplierResolver->resolve($transaction->getBin());

        return \round($commission, 2);
    }
}
