<?php

declare(strict_types=1);

namespace App\Test\Integration\CalculateConsoleCommand;

use App\BinCountry\LookUpBinListCountryResolver;
use App\CalculateConsoleCommand;
use App\Calculation\BinMultiplier\BinMultiplierResolver;
use App\Calculation\TransactionCommissionCalculator;
use App\Collection\Collection;
use App\CurrencyRates\FetchRatesAdapter\ApiLayerExchangeRatesAdapter;
use App\CurrencyRates\RateResolver;
use App\CurrencyRates\RatesProvider\MemoryCacheRatesProvider;
use App\Input\File\FileInputProvider;
use App\Input\File\FileReader;
use App\Money\MoneyConverter;
use App\Test\Integration\JsonFixtureAwareTrait;
use App\Transaction\TransactionDeserializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class MockedHttpResponseTest extends TestCase
{
    use JsonFixtureAwareTrait;

    /**
     * @dataProvider getTestData
     *
     * @throws \JsonException
     */
    public function testExecuteCalculateCommandWithMockedHttpResponse(
        string $targetCurrency,
        array $countryCodesCollection,
        float|int $inCollectionMultiplier,
        float|int $notInCollectionMultiplier,
        string $inputFilePath,
        string $outputFilePath,
        string $ratesFilePath,
        string $binsToCountryCodesFilePath,
    ) : void {
        $ratesHttpClient = $this->mockRatesHttpClient($ratesFilePath);
        $binToCountryHttpClient = $this->mockBinToCountryHttpClient($binsToCountryCodesFilePath);

        $transactionCalculator = new TransactionCommissionCalculator(
            new MoneyConverter(
                new RateResolver(
                    new MemoryCacheRatesProvider(
                        new ApiLayerExchangeRatesAdapter(
                            $ratesHttpClient,
                            'not-really-an-api-key'
                        )
                    )
                )
            ),
            new BinMultiplierResolver(
                new LookUpBinListCountryResolver($binToCountryHttpClient),
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
                EuRegionCaseTestConstants::RATES_HTTP_RESPONSE_FIXTURE
            ),
            EuRegionCaseTestConstants::getCaseFixturePath(
                EuRegionCaseTestConstants::EU_REGION_CASE,
                EuRegionCaseTestConstants::BIN_TO_COUNTRY_HTTP_CLIENT_FIXTURE
            ),
        ];
    }

    private function mockRatesHttpClient(string $fixturePath) : HttpClientInterface
    {
        $mockResponse = $this->getRawFromFile($fixturePath);

        return new MockHttpClient(new MockResponse($mockResponse));
    }

    private function mockBinToCountryHttpClient(string $fixturePath) : HttpClientInterface
    {
        $binToCountryDecoded = $this->decodeFromFile($fixturePath);
        $factory = static fn ($method, $url) => new MockResponse(\json_encode($binToCountryDecoded[$url] ?? '', JSON_THROW_ON_ERROR));

        return new MockHttpClient($factory);
    }
}
