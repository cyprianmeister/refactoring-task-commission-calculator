<?php

declare(strict_types=1);

namespace App\Input;

interface InputProviderInterface
{
    public function setSource(string $source) : void;

    /**
     * @return iterable<string>
     */
    public function provide() : iterable;
}
