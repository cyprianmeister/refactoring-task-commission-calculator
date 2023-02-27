<?php

declare(strict_types=1);

namespace App\Service\Calculation;

use App\Service\Calculation\BinMultiplier\BinMultiplierResolverInterface;
use App\Service\Money\Money;
use App\Service\Money\MoneyConverterInterface;
use App\Service\Transaction\Transaction;

class TransactionCommissionCalculator implements TransactionCalculatorInterface
{
    public function __construct(
        private readonly MoneyConverterInterface $moneyConverter,
        private readonly BinMultiplierResolverInterface $multiplierProvider,
        private readonly string $targetCurrency
    ) {
    }

    public function calculate(Transaction $transaction) : float|int
    {
        $money = $this->moneyConverter->convert(
            Money::create($transaction->getAmount(), $transaction->getCurrency()),
            $this->targetCurrency
        );

        $commission = $money->getAmount() * $this->multiplierProvider->resolve($transaction->getBin());

        return \round($commission, 2);
    }
}
