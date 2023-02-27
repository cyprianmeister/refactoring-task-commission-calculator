<?php

declare(strict_types=1);

namespace App\Service\Transaction;

interface TransactionDeserializable
{
    public function deserialize(string $serializedTransaction) : Transaction;
}
