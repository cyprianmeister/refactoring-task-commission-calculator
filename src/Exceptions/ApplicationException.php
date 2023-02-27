<?php

declare(strict_types=1);

namespace App\Exceptions;

class ApplicationException extends \Exception
{
    public const MESSAGE = 'Execution terminated - error occurred.';

    protected $message = self::MESSAGE;

    public function __construct(?string $message = null, \Throwable $previous = null)
    {
        parent::__construct($message ?? $this->message, (int) $previous?->getCode(), $previous);
    }
}
