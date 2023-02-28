<?php

declare(strict_types=1);

namespace App\Exception;

class RatesProviderException extends ApplicationException
{
    protected $message = 'Problem with currency exchange rates provider occurred.';
}
