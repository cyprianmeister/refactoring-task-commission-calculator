<?php

declare(strict_types=1);

namespace App\Calculation;

use App\Transaction\Transaction;

interface TransactionCalculatorInterface
{
    public function calculate(Transaction $transaction) : float|int;
}
