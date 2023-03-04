<?php

declare(strict_types=1);

namespace App\Test\Unit\Calculation\BinMultiplier;

use App\BinCountry\BinCountryResolverInterface;
use App\Calculation\BinMultiplier\BinMultiplierResolver;
use App\Collection\Collection;
use PHPUnit\Framework\TestCase;

class BinMultiplierResolverTest extends TestCase
{
    private BinCountryResolverInterface $cardBinToCountryProvider;

    protected function setUp() : void
    {
        $this->cardBinToCountryProvider = $this->getMockBuilder(BinCountryResolverInterface::class)->getMock();
    }

    /**
     * @dataProvider getTestData
     */
    public function testResolveBinToMultiplier(array $inputData, float $expected) : void
    {
        /**
         * @phpstan-ignore-next-line
         */
        $this->cardBinToCountryProvider->expects($this->once())
            ->method('resolve')
            ->with($this->equalTo($inputData['bin']))
            ->willReturn($inputData['countryCode']);

        $binMultiplierResolver = new BinMultiplierResolver(
            $this->cardBinToCountryProvider,
            new Collection($inputData['countries']),
            $inputData['inCollectionMultiplier'],
            $inputData['notInCollectionMultiplier'],
        );

        $result = $binMultiplierResolver->resolve($inputData['bin']);

        $this->assertEquals($expected, $result);
    }

    public function getTestData() : array
    {
        return [
            'US in' => [
                [
                    'bin' => '41417360',
                    'countryCode' => 'US',
                    'countries' => ['DE', 'PL', 'FR', 'US'],
                    'inCollectionMultiplier' => 0.11,
                    'notInCollectionMultiplier' => 0.12,
                ],
                0.11,
            ],
            'US not in' => [
                [
                    'bin' => '41417360',
                    'countryCode' => 'US',
                    'countries' => ['DE', 'PL', 'FR'],
                    'inCollectionMultiplier' => 0.11,
                    'notInCollectionMultiplier' => 0.12,
                ],
                0.12,
            ],
            'US empty collection' => [
                [
                    'bin' => '41417360',
                    'countryCode' => 'US',
                    'countries' => [],
                    'inCollectionMultiplier' => 0.11,
                    'notInCollectionMultiplier' => 0.12,
                ],
                0.12,
            ],
            'DK only one in collection' => [
                [
                    'bin' => '41417360',
                    'countryCode' => 'DK',
                    'countries' => ['DK'],
                    'inCollectionMultiplier' => 2,
                    'notInCollectionMultiplier' => 3,
                ],
                2,
            ],
            'DK phrase in collection' => [
                [
                    'bin' => '41417360',
                    'countryCode' => 'DK',
                    'countries' => ['DKA', 'ADK'],
                    'inCollectionMultiplier' => 4.5,
                    'notInCollectionMultiplier' => 10.5,
                ],
                10.5,
            ],
        ];
    }
}
