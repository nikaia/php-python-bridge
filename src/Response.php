<?php

declare(strict_types=1);

namespace Nikaia\PythonBridge;

class Response
{
    public function __construct(
        protected string $output,
    ) {
    }

    public function output(): string
    {
        return $this->output;
    }

    public function json(): array
    {
        return json_decode($this->output(), true);
    }
}
