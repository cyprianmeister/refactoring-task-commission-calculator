<?php

declare(strict_types=1);

namespace App\Test\Unit\Money;

use App\CurrencyRates\RateResolverInterface;
use App\Money\Money;
use App\Money\MoneyConverter;
use PHPUnit\Framework\TestCase;

class MoneyConverterTest extends TestCase
{
    private RateResolverInterface $rateResolver;

    private MoneyConverter $moneyConverter;

    protected function setUp() : void
    {
        $this->rateResolver = $this->getMockBuilder(RateResolverInterface::class)->getMock();
        $this->moneyConverter = new MoneyConverter(
            $this->rateResolver
        );
    }

    /**
     * @dataProvider getTestData
     */
    public function testConvertMoneyToAnotherCurrency(Money $money, string $targetCurrency, float $rate, Money $expected) : void
    {
        /**
         * @phpstan-ignore-next-line
         */
        $this->rateResolver->expects($this->once())
            ->method('resolve')
            ->with($this->equalTo($money->getCurrency()), $this->equalTo($targetCurrency))
            ->willReturn($rate);

        $result = $this->moneyConverter->convert($money, $targetCurrency);

        $this->assertEquals($expected, $result);
    }

    public function getTestData() : array
    {
        return [
            '50 USD to EUR' => [
                Money::create(50.00, 'USD'),
                'EUR',
                1.072036,
                Money::create(46.64022476857121, 'EUR'),
            ],
            '100 PLN to EUR' => [
                Money::create(100.00, 'PLN'),
                'EUR',
                4.769866,
                Money::create(20.964949539462953, 'EUR'),
            ],
            '200 GBP to EUR' => [
                Money::create(200.00, 'GBP'),
                'EUR',
                0.8901,
                Money::create(224.69385462307605, 'EUR'),
            ],
            '100 GBP to EUR' => [
                Money::create(100.00, 'GBP'),
                'EUR',
                0.8901,
                Money::create(112.34692731153802, 'EUR'),
            ],
            '100 EUR to EUR' => [
                Money::create(100.00, 'EUR'),
                'EUR',
                1,
                Money::create(100, 'EUR'),
            ],
        ];
    }
}
