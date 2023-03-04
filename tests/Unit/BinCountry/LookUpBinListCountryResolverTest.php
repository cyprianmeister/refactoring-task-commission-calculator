<?php

declare(strict_types=1);

namespace App\Test\Unit\BinCountry;

use App\BinCountry\LookUpBinListCountryResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class LookUpBinListCountryResolverTest extends TestCase
{
    /**
     * @dataProvider getTestData
     */
    public function testResolveBinToCountry(string $bin, array $mockResponse, string $expected) : void
    {
        $httpClient = new MockHttpClient(
            new MockResponse(\json_encode($mockResponse, JSON_THROW_ON_ERROR))
        );

        $resolver = new LookUpBinListCountryResolver($httpClient);

        $result = $resolver->resolve($bin);

        $this->assertEquals($expected, $result);
    }

    public function getTestData() : array
    {
        return [
            '45717360 to DK' => [
                '45717360',
                [
                    'number' => [
                        'length' => 16,
                        'luhn' => true,
                    ],
                    'scheme' => 'visa',
                    'type' => 'debit',
                    'brand' => 'Visa/Dankort',
                    'prepaid' => false,
                    'country' => [
                        'numeric' => '208',
                        'alpha2' => 'DK',
                        'name' => 'Denmark',
                        'emoji' => '🇩🇰',
                        'currency' => 'DKK',
                        'latitude' => 56,
                        'longitude' => 10,
                    ],
                    'bank' => [
                        'name' => 'Jyske Bank',
                        'url' => 'www.jyskebank.dk',
                        'phone' => '+4589893300',
                        'city' => 'Hjørring',
                    ],
                ],
                'DK',
            ],
            '516793 to LT' => [
                '516793',
                [
                    'number' => [
                    ],
                    'scheme' => 'mastercard',
                    'type' => 'debit',
                    'brand' => 'Debit',
                    'country' => [
                        'numeric' => '440',
                        'alpha2' => 'LT',
                        'name' => 'Lithuania',
                        'emoji' => '🇱🇹',
                        'currency' => 'EUR',
                        'latitude' => 56,
                        'longitude' => 24,
                    ],
                    'bank' => [
                    ],
                ],
                'LT',
            ],
            '45417360 to JP' => [
                '45417360',
                [
                    'number' => [
                        'length' => 16,
                        'luhn' => true,
                    ],
                    'scheme' => 'visa',
                    'type' => 'credit',
                    'brand' => 'Traditional',
                    'prepaid' => false,
                    'country' => [
                        'numeric' => '392',
                        'alpha2' => 'JP',
                        'name' => 'Japan',
                        'emoji' => '🇯🇵',
                        'currency' => 'JPY',
                        'latitude' => 36,
                        'longitude' => 138,
                    ],
                    'bank' => [
                        'name' => 'CREDIT SAISON CO., LTD.',
                        'url' => 'corporate.saisoncard.co.jp',
                        'phone' => '(03)3988-2111',
                    ],
                ],
                'JP',
            ],
            '41417360 to US' => [
                '41417360',
                [
                    'number' => [
                    ],
                    'scheme' => 'visa',
                    'country' => [
                        'numeric' => '840',
                        'alpha2' => 'US',
                        'name' => 'United States of America',
                        'emoji' => '🇺🇸',
                        'currency' => 'USD',
                        'latitude' => 38,
                        'longitude' => -97,
                    ],
                    'bank' => [
                        'name' => 'VERMONT NATIONAL BANK',
                        'url' => 'www.communitynationalbank.com',
                        'phone' => '(802) 744-2287',
                    ],
                ],
                'US',
            ],
            '4745030 to GB' => [
                '4745030',
                [
                    'number' => [
                        'length' => 16,
                        'luhn' => true,
                    ],
                    'scheme' => 'visa',
                    'type' => 'debit',
                    'brand' => 'Traditional',
                    'prepaid' => null,
                    'country' => [
                        'numeric' => '826',
                        'alpha2' => 'GB',
                        'name' => 'United Kingdom of Great Britain and Northern Ireland',
                        'emoji' => '🇬🇧',
                        'currency' => 'GBP',
                        'latitude' => 54,
                        'longitude' => -2,
                    ],
                    'bank' => [
                    ],
                ],
                'GB',
            ],
        ];
    }
}
