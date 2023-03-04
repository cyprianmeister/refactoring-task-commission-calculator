<?php

declare(strict_types=1);

namespace App\Exception;

class FetchRatesAdapterException extends ApplicationException
{
    protected $message = 'Could not fetch currency exchange rates.';
}
