<?php

declare(strict_types=1);

namespace App\Exceptions;

class FileIsNotReadableException extends ApplicationException
{
    protected $message = 'Given input file is not readable.';
}
