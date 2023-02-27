<?php

declare(strict_types=1);

namespace App\Service\CurrencyRates\RatesProvider;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiRatesProvider extends AbstractRatesProvider
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

    /**
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws \JsonException
     */
    protected function fetchData() : array
    {
        $response = $this->httpClient->request(
            $this->method,
            $this->url,
            [
                'headers' => [
                    'apikey' => $this->apiKey,
                ],
                'query' => [
                    'base' => $this->baseCurrency,
                ],
            ]
        )->getContent();

        return \json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    }

    protected function extractData(array $fetched) : ?array
    {
        return $fetched['rates'] ?? null;
    }
}
