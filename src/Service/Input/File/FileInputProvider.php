<?php

declare(strict_types=1);

namespace App\Service\Input\File;

use App\Service\Input\InputProviderInterface;

class FileInputProvider implements InputProviderInterface
{
    public function __construct(private readonly FileReaderInterface $fileReader)
    {
    }

    public function setSource(string $source) : void
    {
        $this->fileReader->setFilePath($source);
    }

    /**
     * @return iterable<string>
     */
    public function provide() : iterable
    {
        return $this->fileReader->readByLine();
    }
}
