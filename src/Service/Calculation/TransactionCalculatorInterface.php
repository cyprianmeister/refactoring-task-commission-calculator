<?php

declare(strict_types=1);

namespace App\Service\Calculation;

use App\Service\Transaction\Transaction;

interface TransactionCalculatorInterface
{
    public function calculate(Transaction $transaction) : float|int;
}
