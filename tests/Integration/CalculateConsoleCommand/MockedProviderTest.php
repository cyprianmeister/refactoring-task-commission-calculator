<?php

declare(strict_types=1);

namespace App\Test\Integration\CalculateConsoleCommand;

use App\BinCountry\BinCountryResolverInterface;
use App\CalculateConsoleCommand;
use App\Calculation\BinMultiplier\BinMultiplierResolver;
use App\Calculation\TransactionCommissionCalculator;
use App\Collection\Collection;
use App\CurrencyRates\RateResolver;
use App\CurrencyRates\RatesProvider\RatesProviderInterface;
use App\Input\File\FileInputProvider;
use App\Input\File\FileReader;
use App\Money\MoneyConverter;
use App\Test\Integration\JsonFixtureAwareTrait;
use App\Transaction\TransactionDeserializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

final class MockedProviderTest extends TestCase
{
    use JsonFixtureAwareTrait;

    /**
     * @dataProvider getTestData
     *
     * @throws \JsonException
     */
    public function testExecuteCalculateCommandWithMockedProviders(
        string $targetCurrency,
        array $countryCodesCollection,
        float|int $inCollectionMultiplier,
        float|int $notInCollectionMultiplier,
        string $inputFilePath,
        string $outputFilePath,
        string $ratesFilePath,
        string $binsToCountryCodesFilePath,
    ) : void {
        $ratesProviderMock = $this->mockRatesProvider($targetCurrency, $ratesFilePath);
        $binToCountryProviderMock = $this->mockBinToCountryProvider($binsToCountryCodesFilePath);

        $transactionCalculator = new TransactionCommissionCalculator(
            new MoneyConverter(
                new RateResolver($ratesProviderMock)
            ),
            new BinMultiplierResolver(
                $binToCountryProviderMock,
                new Collection($countryCodesCollection),
                $inCollectionMultiplier,
                $notInCollectionMultiplier
            ),
            $targetCurrency
        );

        $command = new CalculateConsoleCommand(
            new FileInputProvider(new FileReader()),
            $transactionCalculator,
            new TransactionDeserializer(
                new Serializer([new ObjectNormalizer()], [new JsonEncoder()])
            )
        );

        $commandTester = new CommandTester($command);
        $commandTester->execute(['inputFile' => $inputFilePath]);

        $commandTester->assertCommandIsSuccessful();

        $this->assertStringEqualsFile($outputFilePath, $commandTester->getDisplay());
    }

    public function getTestData() : \Generator
    {
        return yield [
            EuRegionCaseTestConstants::EUR,
            EuRegionCaseTestConstants::EU_COUNTRY_CODES,
            EuRegionCaseTestConstants::IS_EU_COUNTRY_MULTIPLIER,
            EuRegionCaseTestConstants::IS_NOT_EU_COUNTRY_MULTIPLIER,
            EuRegionCaseTestConstants::getCaseFixturePath(
                EuRegionCaseTestConstants::EU_REGION_CASE,
                EuRegionCaseTestConstants::INPUT_FIXTURE
            ),
            EuRegionCaseTestConstants::getCaseFixturePath(
                EuRegionCaseTestConstants::EU_REGION_CASE,
                EuRegionCaseTestConstants::OUTPUT_FIXTURE
            ),
            EuRegionCaseTestConstants::getCaseFixturePath(
                EuRegionCaseTestConstants::EU_REGION_CASE,
                EuRegionCaseTestConstants::RATES_PROVIDER_FIXTURE
            ),
            EuRegionCaseTestConstants::getCaseFixturePath(
                EuRegionCaseTestConstants::EU_REGION_CASE,
                EuRegionCaseTestConstants::BIN_TO_COUNTRY_PROVIDER_FIXTURE
            ),
        ];
    }

    private function mockRatesProvider(string $targetCurrency, string $fixturePath) : RatesProviderInterface
    {
        $ratesCollection = new Collection($this->decodeFromFile($fixturePath));
        $mock = $this->getMockBuilder(RatesProviderInterface::class)->getMock();
        $mock->expects($this->atLeastOnce())
            ->method('provide')
            ->with($this->equalTo($targetCurrency))
            ->willReturn($ratesCollection);

        return $mock;
    }

    private function mockBinToCountryProvider(string $fixturePath) : BinCountryResolverInterface
    {
        $binToCountryDecoded = $this->decodeFromFile($fixturePath);
        $mock = $this->getMockBuilder(BinCountryResolverInterface::class)->getMock();
        $map = \array_map(static function ($bin, $countryCode) {
            return [(string) $bin, $countryCode];
        }, \array_keys($binToCountryDecoded), $binToCountryDecoded);
        $mock
            ->method('resolve')
            ->willReturnMap($map);

        return $mock;
    }
}
