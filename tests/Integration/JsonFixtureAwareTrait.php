<?php

declare(strict_types=1);

namespace App\Test\Integration;

trait JsonFixtureAwareTrait
{
    /**
     * @throws \JsonException
     */
    private function decodeFromFile(string $fixturePath) : array
    {
        $jsonBinToCountry = $this->getRawFromFile($fixturePath);

        return \json_decode($jsonBinToCountry, true, 512, JSON_THROW_ON_ERROR);
    }

    private function getRawFromFile(string $fixturePath) : string
    {
        return (string) \file_get_contents($fixturePath);
    }
}
