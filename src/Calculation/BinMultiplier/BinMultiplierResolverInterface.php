<?php

declare(strict_types=1);

namespace App\Calculation\BinMultiplier;

interface BinMultiplierResolverInterface
{
    public function resolve(string $bin) : float;
}
