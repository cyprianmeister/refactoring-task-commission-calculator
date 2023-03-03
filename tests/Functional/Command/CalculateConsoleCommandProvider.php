<?php

declare(strict_types=1);

namespace App\Test\Functional\Command;

use App\Test\Functional\Command\Constants\EuRegionCaseTestConstants;

final class CalculateConsoleCommandProvider
{
    private const EU_REGION_CASE = 'eu_region_case';

    public static function getTestCase() : \Generator
    {
        yield self::EU_REGION_CASE => [
            EuRegionCaseTestConstants::EUR,
            EuRegionCaseTestConstants::EU_COUNTRY_CODES,
            EuRegionCaseTestConstants::IS_EU_COUNTRY_MULTIPLIER,
            EuRegionCaseTestConstants::IS_NOT_EU_COUNTRY_MULTIPLIER,
            EuRegionCaseTestConstants::getCaseFixturePath(
                self::EU_REGION_CASE,
                EuRegionCaseTestConstants::INPUT_FIXTURE
            ),
            EuRegionCaseTestConstants::getCaseFixturePath(
                self::EU_REGION_CASE,
                EuRegionCaseTestConstants::OUTPUT_FIXTURE
            ),
            EuRegionCaseTestConstants::getCaseFixturePath(
                self::EU_REGION_CASE,
                EuRegionCaseTestConstants::RATES_FIXTURE
            ),
            EuRegionCaseTestConstants::getCaseFixturePath(
                self::EU_REGION_CASE,
                EuRegionCaseTestConstants::BIN_TO_COUNTRY_FIXTURE
            ),
        ];
    }
}
