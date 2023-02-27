<?php

declare(strict_types=1);

namespace App\Service\CurrencyRates\RatesProvider;

use App\Exceptions\RatesProviderException;
use App\Service\Collection\CollectionInterface;

interface RatesProviderInterface
{
    /**
     * @throws RatesProviderException
     */
    public function provide(string $baseCurrency) : CollectionInterface;
}
