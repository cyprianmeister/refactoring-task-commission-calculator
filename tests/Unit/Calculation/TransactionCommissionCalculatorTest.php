<?php

namespace App\Test\Unit\Calculation;

use App\Calculation\BinMultiplier\BinMultiplierResolverInterface;
use App\Calculation\TransactionCommissionCalculator;
use App\Money\Money;
use App\Money\MoneyConverterInterface;
use App\Transaction\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionCommissionCalculatorTest extends TestCase
{
    private MoneyConverterInterface $moneyConverter;

    private BinMultiplierResolverInterface $binMultiplierResolver;

    protected function setUp() : void
    {
        $this->moneyConverter = $this->getMockBuilder(MoneyConverterInterface::class)->getMock();
        $this->binMultiplierResolver = $this->getMockBuilder(BinMultiplierResolverInterface::class)->getMock();
    }

    /**
     * @dataProvider getTestData
     */
    public function testCalculateTransactionCommission(Transaction $transaction, string $targetCurrency, array $mockData, float $expected) : void
    {
        /**
         * @phpstan-ignore-next-line
         */
        $this->moneyConverter->expects($this->once())
            ->method('convert')
            ->with($this->equalTo(
                    Money::create($transaction->getAmount(), $transaction->getCurrency())
                ),
                $this->equalTo($targetCurrency)
            )
            ->willReturn($mockData['convertedMoney']);

        $this->binMultiplierResolver->expects($this->once())
            ->method('resolve')
            ->with($this->equalTo($transaction->getBin()))
            ->willReturn($mockData['multiplier']);

        $commissionCalculator = new TransactionCommissionCalculator(
            $this->moneyConverter,
            $this->binMultiplierResolver,
            $targetCurrency
        );

        $result = $commissionCalculator->calculate($transaction);

        $this->assertEquals($expected, $result);
    }

    public function getTestData() : array
    {
        return [
            '{"bin":"45717360","amount":"100.00","currency":"EUR"}' => [
                Transaction::create('45717360', 100.00, 'EUR'),
                'EUR',
                [
                    'convertedMoney' => Money::create(100.00, 'EUR'),
                    'multiplier' => 0.01,
                ],
                1,
            ],
            '{"bin":"516793","amount":"50.00","currency":"USD"}' => [
                Transaction::create('516793', 50.00, 'USD'),
                'EUR',
                [
                    'convertedMoney' => Money::create(46.64022476857121, 'EUR'),
                    'multiplier' => 0.01,
                ],
                0.47,
            ],
            '{"bin":"45417360","amount":"10000.00","currency":"JPY"}' => [
                Transaction::create('45417360', 10000.00, 'JPY'),
                'EUR',
                [
                    'convertedMoney' => Money::create(69.11922743946516, 'EUR'),
                    'multiplier' => 0.02,
                ],
                1.38,
            ],
            '{"bin":"41417360","amount":"130.00","currency":"USD"}' => [
                Transaction::create('41417360', 130.00, 'USD'),
                'EUR',
                [
                    'convertedMoney' => Money::create(122.06355003995235, 'EUR'),
                    'multiplier' => 0.02,
                ],
                2.44,
            ],
            '{"bin":"4745030","amount":"2000.00","currency":"GBP"}' => [
                Transaction::create('4745030', 2000.00, 'GBP'),
                'EUR',
                [
                    'convertedMoney' => Money::create(2260.052998242809, 'EUR'),
                    'multiplier' => 0.02,
                ],
                45.2,
            ],
        ];
    }
}
