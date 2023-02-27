<?php

declare(strict_types=1);

namespace App\Service\Input\File;

use App\Exceptions\FileIsNotReadableException;

interface FileReaderInterface
{
    public function setFilePath(string $filePath) : void;

    /**
     * @throws FileIsNotReadableException
     *
     * @return iterable<string>
     */
    public function readByLine() : iterable;
}
