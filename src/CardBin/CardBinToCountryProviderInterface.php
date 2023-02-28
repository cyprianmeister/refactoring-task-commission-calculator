<?php

declare(strict_types=1);

namespace App\CardBin;

use App\Exception\CardBinToCountryProviderException;

interface CardBinToCountryProviderInterface
{
    /**
     * @throws CardBinToCountryProviderException
     */
    public function provide(string $bin) : ?string;
}
