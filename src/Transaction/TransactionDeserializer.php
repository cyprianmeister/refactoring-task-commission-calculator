<?php

declare(strict_types=1);

namespace App\Transaction;

use Symfony\Component\Serializer\SerializerInterface;

final class TransactionDeserializer implements TransactionDeserializable
{
    private const FROM_FORMAT = 'json';

    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    public function deserialize(string $serializedTransaction, string $fromFormat = self::FROM_FORMAT) : Transaction
    {
        return $this->serializer->deserialize($serializedTransaction, Transaction::class, $fromFormat);
    }
}
