<?php

declare(strict_types=1);

namespace App\Service\CardBin;

use App\Exceptions\CardBinToCountryProviderException;

interface CardBinToCountryProviderInterface
{
    /**
     * @throws CardBinToCountryProviderException
     */
    public function provide(string $bin) : ?string;
}
