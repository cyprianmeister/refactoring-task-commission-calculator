<?php

declare(strict_types=1);

namespace App\Test\Integration\CalculateConsoleCommand;

final class EuRegionCaseTestConstants
{
    public const FIXTURES_PATH = __DIR__ . '/fixtures/';

    public const INPUT_FIXTURE = 'input.txt';

    public const OUTPUT_FIXTURE = 'output.txt';

    public const RATES_PROVIDER_FIXTURE = 'eur_rates.json';

    public const BIN_TO_COUNTRY_PROVIDER_FIXTURE = 'bin_to_country.json';

    public const RATES_HTTP_RESPONSE_FIXTURE = 'eur_rates_http_response.json';

    public const BIN_TO_COUNTRY_HTTP_CLIENT_FIXTURE = 'bin_to_country_http_responses.json';

    public const EUR = 'EUR';

    public const EU_COUNTRY_CODES = [
        'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT',
        'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK',
    ];

    public const IS_EU_COUNTRY_MULTIPLIER = 0.01;

    public const IS_NOT_EU_COUNTRY_MULTIPLIER = 0.02;

    public const EU_REGION_CASE = 'eu_region_case';

    public static function getCaseFixturePath(string $case, string $fixtureFile) : string
    {
        return self::FIXTURES_PATH . $case . '/' . $fixtureFile;
    }
}
