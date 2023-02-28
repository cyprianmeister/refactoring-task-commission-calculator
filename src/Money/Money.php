<?php

declare(strict_types=1);

namespace App\Money;

class Money
{
    private float $amount;

    private string $currency;

    private function __construct(float $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public static function create(float $amount, string $currency) : self
    {
        return new self($amount, $currency);
    }

    public function getAmount() : float
    {
        return $this->amount;
    }

    public function getCurrency() : string
    {
        return $this->currency;
    }
}
