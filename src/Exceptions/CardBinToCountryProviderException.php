<?php

declare(strict_types=1);

namespace App\Exceptions;

class CardBinToCountryProviderException extends ApplicationException
{
    protected $message = 'Problem with card Bank Identification Number provider occurred.';
}
