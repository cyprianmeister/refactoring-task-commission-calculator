<?php

declare(strict_types=1);

namespace App\Test\Command;

use App\ConsoleCommand\CalculateConsoleCommand;
use App\Service\Calculation\BinMultiplier\BinMultiplierResolver;
use App\Service\Calculation\TransactionCommissionCalculator;
use App\Service\CardBin\CardBinToCountryProviderInterface;
use App\Service\Collection\Collection;
use App\Service\CurrencyRates\RateResolver;
use App\Service\CurrencyRates\RatesProvider\RatesProviderInterface;
use App\Service\Input\File\FileInputProvider;
use App\Service\Input\File\FileReader;
use App\Service\Money\MoneyConverter;
use App\Service\Transaction\TransactionDeserializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

final class CalculateConsoleCommandTest extends TestCase
{
    private RatesProviderInterface $ratesProviderMock;

    private CardBinToCountryProviderInterface $binToCountryProviderMock;

    /**
     * @dataProvider provider
     */
    public function testExecute(
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

    private function mockRatesProvider(string $targetCurrency, string $fixturePath) : void
    {
        $jsonRates = \file_get_contents($fixturePath);
        $ratesCollection = new Collection(
            \json_decode($jsonRates, true, 512, JSON_THROW_ON_ERROR)
        );

        $this->ratesProviderMock = $this->getMockBuilder(RatesProviderInterface::class)->getMock();
        $this->ratesProviderMock->expects($this->atLeastOnce())
            ->method('provide')
            ->with($this->equalTo($targetCurrency))
            ->willReturn($ratesCollection);
    }

    private function mockBinToCountryProvider(string $fixturePath) : void
    {
        $jsonBinToCountry = \file_get_contents($fixturePath);
        $binToCountryDecoded = \json_decode($jsonBinToCountry, true, 512, JSON_THROW_ON_ERROR);

        $this->binToCountryProviderMock = $this->getMockBuilder(CardBinToCountryProviderInterface::class)->getMock();
        $map = \array_map(static function ($bin, $countryCode) {
            return [(string) $bin, $countryCode];
        }, \array_keys($binToCountryDecoded), $binToCountryDecoded);
        $this->binToCountryProviderMock
            ->method('provide')
            ->willReturnMap($map);
    }
}
