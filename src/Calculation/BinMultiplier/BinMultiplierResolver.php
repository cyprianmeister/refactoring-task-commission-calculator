<?php

declare(strict_types=1);

namespace App\Calculation\BinMultiplier;

use App\BinCountry\BinCountryResolverInterface;
use App\Collection\CollectionInterface;
use App\Exception\BinCountryResolverException;

final class BinMultiplierResolver implements BinMultiplierResolverInterface
{
    public function __construct(
        private readonly BinCountryResolverInterface $binToCountryResolver,
        private readonly CollectionInterface $countriesCollection,
        private readonly float $inCollectionMultiplier,
        private readonly float $notInCollectionMultiplier,
    ) {
    }

    /**
     * @throws BinCountryResolverException
     */
    public function resolve(string $bin) : float
    {
        $countryCode = $this->binToCountryResolver->resolve($bin);

        $inCollection = $this->countriesCollection->isInCollection($countryCode);

        return $inCollection ? $this->inCollectionMultiplier : $this->notInCollectionMultiplier;
    }
}
