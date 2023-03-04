<?php

declare(strict_types=1);

namespace App\Test\Functional\Command;

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
use App\Transaction\TransactionDeserializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

final class CalculateConsoleCommandTest extends TestCase
{
    private RatesProviderInterface $ratesProviderMock;

    private BinCountryResolverInterface $binToCountryProviderMock;

    /**
     * @dataProvider provider
     *
     * @throws \JsonException
     */
    public function testExecuteCalculateCommand(
        string $targetCurrency,
        array $countryCodesCollection,
        float|int $inCollectionMultiplier,
        float|int $notInCollectionMultiplier,
        string $inputFilePath,
        string $outputFilePath,
        string $ratesFilePath,
        string $binsToCountryCodesFilePath,
    ) : void {
        $this->mockRatesProvider($targetCurrency, $ratesFilePath);
        $this->mockBinToCountryProvider($binsToCountryCodesFilePath);

        $transactionCalculator = new TransactionCommissionCalculator(
            new MoneyConverter(
                new RateResolver($this->ratesProviderMock)
            ),
            new BinMultiplierResolver(
                $this->binToCountryProviderMock,
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

    public function provider() : \Generator
    {
        return CalculateConsoleCommandProvider::getTestCase();
    }

    /**
     * @throws \JsonException
     */
    private function mockRatesProvider(string $targetCurrency, string $fixturePath) : void
    {
        $ratesCollection = new Collection($this->decodeFromFile($fixturePath));
        $this->ratesProviderMock = $this->getMockBuilder(RatesProviderInterface::class)->getMock();
        $this->ratesProviderMock->expects($this->atLeastOnce())
            ->method('provide')
            ->with($this->equalTo($targetCurrency))
            ->willReturn($ratesCollection);
    }

    /**
     * @throws \JsonException
     */
    private function mockBinToCountryProvider(string $fixturePath) : void
    {
        $binToCountryDecoded = $this->decodeFromFile($fixturePath);
        $this->binToCountryProviderMock = $this->getMockBuilder(BinCountryResolverInterface::class)->getMock();
        $map = \array_map(static function ($bin, $countryCode) {
            return [(string) $bin, $countryCode];
        }, \array_keys($binToCountryDecoded), $binToCountryDecoded);
        $this->binToCountryProviderMock
            ->method('resolve')
            ->willReturnMap($map);
    }

    /**
     * @throws \JsonException
     */
    private function decodeFromFile(string $fixturePath) : array
    {
        $jsonBinToCountry = (string) \file_get_contents($fixturePath);

        return \json_decode($jsonBinToCountry, true, 512, JSON_THROW_ON_ERROR);
    }
}
