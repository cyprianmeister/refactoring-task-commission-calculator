<?php

declare(strict_types=1);

namespace App\Service\Calculation\BinMultiplier;

interface BinMultiplierResolverInterface
{
    public function resolve(string $bin) : float;
}
