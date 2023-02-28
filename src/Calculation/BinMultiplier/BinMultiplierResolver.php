<?php

declare(strict_types=1);

namespace App\Calculation\BinMultiplier;

use App\CardBin\CardBinToCountryProviderInterface;
use App\Collection\CollectionInterface;
use App\Exception\CardBinToCountryProviderException;

final class BinMultiplierResolver implements BinMultiplierResolverInterface
{
    public function __construct(
        private readonly CardBinToCountryProviderInterface $binToCountryProvider,
        private readonly CollectionInterface $countriesCollection,
        private readonly float $inCollectionMultiplier,
        private readonly float $notInCollectionMultiplier,
    ) {
    }

    /**
     * @throws CardBinToCountryProviderException
     */
    public function resolve(string $bin) : float
    {
        $countryCode = $this->binToCountryProvider->provide($bin);

        $inCollection = $this->countriesCollection->isInCollection($countryCode);

        return $inCollection ? $this->inCollectionMultiplier : $this->notInCollectionMultiplier;
    }
}
