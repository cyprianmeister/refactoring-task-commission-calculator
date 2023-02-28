<?php

declare(strict_types=1);

namespace App\Input\File;

use App\Exception\FileIsNotReadableException;

final class FileReader implements FileReaderInterface
{
    private string $filePath;

    /**
     * @var resource
     */
    private $handle;

    public function setFilePath(string $filePath) : void
    {
        $this->filePath = $filePath;
    }

    /**
     * @throws FileIsNotReadableException
     *
     * @return iterable<string>
     */
    public function readByLine() : iterable
    {
        try {
            $this->openFile();

            while (!$this->isEndOfFile()) {
                yield $this->readFileLine();
            }

            $this->closeFile();
        } catch (\Throwable $exception) {
            throw new FileIsNotReadableException(previous: $exception);
        }
    }

    private function openFile() : void
    {
        if (!$handle = @\fopen($this->filePath, 'rb')) {
            throw new \InvalidArgumentException('Cannot open file for reading: ' . $this->filePath);
        }
        $this->handle = $handle;
    }

    private function readFileLine() : string
    {
        return (string) \fgets($this->handle);
    }

    private function isEndOfFile() : bool
    {
        return \feof($this->handle);
    }

    private function closeFile() : void
    {
        \fclose($this->handle);
    }
}
