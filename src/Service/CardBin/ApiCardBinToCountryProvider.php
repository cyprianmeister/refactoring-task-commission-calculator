<?php

declare(strict_types=1);

namespace App\Service\CardBin;

use App\Exceptions\CardBinToCountryProviderException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiCardBinToCountryProvider implements CardBinToCountryProviderInterface
{
    private const DEFAULT_REQUEST_URL = 'https://lookup.binlist.net/';

    private const DEFAULT_REQUEST_METHOD = 'GET';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $url = self::DEFAULT_REQUEST_URL,
        private readonly string $method = self::DEFAULT_REQUEST_METHOD,
    ) {
    }

    public function provide(string $bin) : ?string
    {
        try {
            $data = $this->fetchData($bin);

            return $this->extractData($data);
        } catch (\Throwable $exception) {
            throw new CardBinToCountryProviderException(previous: $exception);
        }
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws \JsonException
     */
    private function fetchData(string $bin) : array
    {
        $response = $this->httpClient->request($this->method, $this->url . $bin)->getContent();

        return \json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    }

    private function extractData(array $fetched) : ?string
    {
        return $fetched['country']['alpha2'] ?? null;
    }
}
