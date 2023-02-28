<?php

declare(strict_types=1);

namespace App\Exception;

class FileIsNotReadableException extends ApplicationException
{
    protected $message = 'Given input file is not readable.';
}
