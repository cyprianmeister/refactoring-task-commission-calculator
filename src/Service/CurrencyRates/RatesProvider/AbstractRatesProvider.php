<?php

declare(strict_types=1);

namespace App\Service\CurrencyRates\RatesProvider;

use App\Exceptions\RatesProviderException;
use App\Service\Collection\Collection;
use App\Service\Collection\CollectionInterface;

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
