<?php

declare(strict_types=1);

namespace App\Service\Input;

interface InputProviderInterface
{
    public function setSource(string $source) : void;

    /**
     * @return iterable<string>
     */
    public function provide() : iterable;
}
