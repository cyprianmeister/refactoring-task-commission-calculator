<?php

declare(strict_types=1);

namespace App\BinCountry;

use App\Exception\BinCountryResolverException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class LookUpBinListCountryResolver implements BinCountryResolverInterface
{
    private const DEFAULT_REQUEST_URL = 'https://lookup.binlist.net/';

    private const DEFAULT_REQUEST_METHOD = 'GET';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $url = self::DEFAULT_REQUEST_URL,
        private readonly string $method = self::DEFAULT_REQUEST_METHOD,
    ) {
    }

    public function resolve(string $bin) : string
    {
        try {
            $response = $this->httpClient->request($this->method, $this->url . $bin)->toArray();

            $extracted = $response['country']['alpha2'] ?? [];

            if (empty($extracted)) {
                throw new \ValueError('Country data is empty');
            }

            return $extracted;
        } catch (\Throwable $exception) {
            throw new BinCountryResolverException(previous: $exception);
        }
    }
}
