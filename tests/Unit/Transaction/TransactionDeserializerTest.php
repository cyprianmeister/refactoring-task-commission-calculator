<?php

declare(strict_types=1);

namespace App\Test\Unit\Transaction;

use App\Transaction\Transaction;
use App\Transaction\TransactionDeserializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class TransactionDeserializerTest extends TestCase
{
    private TransactionDeserializer $deserializer;

    protected function setUp() : void
    {
        $this->deserializer = new TransactionDeserializer(
            new Serializer([new ObjectNormalizer()], [new JsonEncoder()])
        );
    }

    /**
     * @dataProvider getTestData
     */
    public function testDeserialize(string $testInput, Transaction $expected) : void
    {
        $result = $this->deserializer->deserialize($testInput);

        $this->assertEquals($expected, $result);
    }

    public function getTestData() : array
    {
        return [
            [
                '{"bin":"45717360","amount":"100.00","currency":"EUR"}',
                Transaction::create('45717360', 100.00, 'EUR'),
            ],
            [
                '{"bin":"516793","amount":"50.00","currency":"USD"}',
                Transaction::create('516793', 50.00, 'USD'),
            ],
            [
                '{"bin":"45417360","amount":"10000.00","currency":"JPY"}',
                Transaction::create('45417360', 10000.00, 'JPY'),
            ],
            [
                '{"bin":"41417360","amount":"130.00","currency":"USD"}',
                Transaction::create('41417360', 130.00, 'USD'),
            ],
            [
                '{"bin":"4745030","amount":"2000.00","currency":"GBP"}',
                Transaction::create('4745030', 2000.00, 'GBP'),
            ],
        ];
    }
}
