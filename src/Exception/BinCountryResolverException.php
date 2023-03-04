<?php

declare(strict_types=1);

namespace App\Exception;

class BinCountryResolverException extends ApplicationException
{
    protected $message = 'Could not fetch BIN country information.';
}
