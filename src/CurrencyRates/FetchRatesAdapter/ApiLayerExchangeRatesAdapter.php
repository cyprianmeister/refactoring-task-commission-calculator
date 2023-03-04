<?php

declare(strict_types=1);

namespace App\CurrencyRates\FetchRatesAdapter;

use App\Exception\FetchRatesAdapterException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ApiLayerExchangeRatesAdapter implements FetchRatesAdapterInterface
{
    private const DEFAULT_REQUEST_URL = 'https://api.apilayer.com/exchangerates_data/latest';

    private const DEFAULT_REQUEST_METHOD = 'GET';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $apiKey,
        private readonly string $url = self::DEFAULT_REQUEST_URL,
        private readonly string $method = self::DEFAULT_REQUEST_METHOD,
    ) {
    }

    public function fetchRates(string $baseCurrency) : array
    {
        try {
            $response = $this->httpClient->request(
                $this->method,
                $this->url,
                [
                    'headers' => [
                        'apikey' => $this->apiKey,
                    ],
                    'query' => [
                        'base' => $baseCurrency,
                    ],
                ]
            )->toArray();

            $extracted = $response['rates'] ?? [];

            if (empty($extracted)) {
                throw new \ValueError('Rates data is empty');
            }

            return $extracted;
        } catch (\Throwable $exception) {
            throw new FetchRatesAdapterException(previous: $exception);
        }
    }
}
