<?php

declare(strict_types=1);

namespace App\Test;

use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    protected string $consolePath;

    public function setUp() : void
    {
        $this->consolePath = \dirname(__DIR__) . '/bin/console';
    }

    public function testConsole() : void
    {
        $output = (string) \shell_exec($this->consolePath);
        $this->assertStringContainsString('Usage:', $output);
        $this->assertStringContainsString('Options:', $output);
        $this->assertStringContainsString('Available commands:', $output);
    }
}
