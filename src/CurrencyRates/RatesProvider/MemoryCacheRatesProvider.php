<?php

declare(strict_types=1);

namespace App\CurrencyRates\RatesProvider;

use App\Collection\Collection;
use App\Collection\CollectionInterface;
use App\CurrencyRates\FetchRatesAdapter\FetchRatesAdapterInterface;
use App\Exception\RatesProviderException;

final class MemoryCacheRatesProvider implements RatesProviderInterface
{
    private array $rates = [];

    private ?string $baseCurrency = null;

    public function __construct(private readonly FetchRatesAdapterInterface $ratesAdapter)
    {
    }

    /**
     * @throws RatesProviderException
     */
    public function provide(string $baseCurrency) : CollectionInterface
    {
        try {
            if ($baseCurrency !== $this->baseCurrency || empty($this->rates)) {
                $this->baseCurrency = $baseCurrency;
                $this->rates = $this->ratesAdapter->fetchRates($baseCurrency);
            }

            return new Collection($this->rates);
        } catch (\Throwable $exception) {
            throw new RatesProviderException(previous: $exception);
        }
    }
}
