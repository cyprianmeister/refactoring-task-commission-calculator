<?php

declare(strict_types=1);

namespace App;

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;

final class Kernel extends Application
{
    protected string $env = 'prod';

    protected bool $debug = false;

    public function __construct(
        iterable $commands = [],
        string $name = 'UNKNOWN',
        string $version = 'UNKNOWN',
        string $env = 'dev',
        bool $debug = true
    ) {
        foreach ($commands as $command) {
            $this->add($command);
        }

        $this->debug = $debug;
        $this->env = $env;

        parent::__construct($name, $version);
    }
}
