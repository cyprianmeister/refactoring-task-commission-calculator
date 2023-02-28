<?php

declare(strict_types=1);

namespace App\CurrencyRates\RatesProvider;

use App\Collection\Collection;
use App\Collection\CollectionInterface;
use App\Exception\RatesProviderException;

abstract class AbstractRatesProvider implements RatesProviderInterface
{
    protected array $rates = [];

    protected ?string $baseCurrency = null;

    public function provide(string $baseCurrency) : CollectionInterface
    {
        try {
            if ($baseCurrency !== $this->baseCurrency || empty($this->rates)) {
                $this->baseCurrency = $baseCurrency;
                $fetched = $this->fetchData();

                if ($extracted = $this->extractData($fetched)) {
                    $this->rates = $extracted;
                }
            }

            return new Collection($this->rates);
        } catch (\Throwable $exception) {
            throw new RatesProviderException(previous: $exception);
        }
    }

    abstract protected function fetchData() : array;

    abstract protected function extractData(array $fetched) : ?array;
}
