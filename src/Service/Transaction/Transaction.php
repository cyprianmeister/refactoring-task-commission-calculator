<?php

declare(strict_types=1);

namespace App\Service\Transaction;

class Transaction
{
    private string $bin;

    private float $amount;

    private string $currency;

    private function __construct(string $bin, float $amount, string $currency)
    {
        $this->bin = $bin;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public static function create(string $bin, float $amount, string $currency) : self
    {
        return new self($bin, $amount, $currency);
    }

    public function getBin() : string
    {
        return $this->bin;
    }

    public function getAmount() : float
    {
        return $this->amount;
    }

    public function getCurrency() : string
    {
        return $this->currency;
    }

    public function setBin(string $bin) : void
    {
        $this->bin = $bin;
    }

    public function setAmount(float $amount) : void
    {
        $this->amount = $amount;
    }

    public function setCurrency(string $currency) : void
    {
        $this->currency = $currency;
    }
}
