<?php

declare(strict_types=1);

namespace App\Service\Transaction;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class TransactionDeserializer implements TransactionDeserializable
{
    private const FROM_FORMAT = JsonEncoder::FORMAT;

    public function __construct(private readonly SerializerInterface $serializer, private readonly ?string $fromFormat = self::FROM_FORMAT)
    {
    }

    public function deserialize(string $serializedTransaction) : Transaction
    {
        return $this->serializer->deserialize($serializedTransaction, Transaction::class, $this->fromFormat);
    }
}
