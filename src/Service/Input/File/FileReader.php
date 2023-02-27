<?php

declare(strict_types=1);

namespace App\Service\Input\File;

use App\Exceptions\FileIsNotReadableException;

class FileReader implements FileReaderInterface
{
    private string $filePath;

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

            if ($this->isFileOpened()) {
                while (!$this->isEndOfFile()) {
                    yield $this->readFileLine();
                }
            }

            $this->closeFile();
        } catch (\Throwable $exception) {
            throw new FileIsNotReadableException(previous: $exception);
        }
    }

    private function openFile() : void
    {
        if (!$this->handle = @\fopen($this->filePath, 'rb')) {
            throw new \InvalidArgumentException('Cannot open file for reading: ' . $this->filePath);
        }
    }

    private function isFileOpened()
    {
        return $this->handle;
    }

    private function readFileLine() : bool|string
    {
        return \fgets($this->handle);
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
