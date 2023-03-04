<?php

declare(strict_types=1);

namespace App\BinCountry;

use App\Exception\BinCountryResolverException;

interface BinCountryResolverInterface
{
    /**
     * @throws BinCountryResolverException
     */
    public function resolve(string $bin) : string;
}
