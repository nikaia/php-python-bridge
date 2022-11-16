<?php

namespace Nikaia\PythonBridge\Tests;

use Nikaia\PythonBridge\BridgeException;
use Nikaia\PythonBridge\Bridge;
use PHPUnit\Framework\TestCase;

class BridgeTest extends TestCase
{
    /** @test */
    public function it_runs_script_correctly_passing_array()
    {
        $response = Bridge::create()
            ->setPython(getenv('PYTHON_BIN'))
            ->setScript(__DIR__ . '/_fixtures/ok.script.py')
            ->pipe(['name' => 'John Doe'])
            ->run();

        $this->assertEquals([
            'name' => 'John Doe',
            'age' => 30,
            'city' => 'New York',
        ], $response->json());
    }

    /** @test */
    public function it_runs_script_correctly_passing_json()
    {
        $response = Bridge::create()
            ->setPython(getenv('PYTHON_BIN'))
            ->setScript(__DIR__ . '/_fixtures/ok.script.py')
            ->pipeRaw('{"name":"John Doe"}')
            ->run();

        $this->assertEquals([
            'name' => 'John Doe',
            'age' => 30,
            'city' => 'New York',
        ], $response->json());
    }

    /** @test */
    public function it_fails_when_nothing_is_piped()
    {
        $this->expectException(BridgeException::class);
        $this->expectExceptionMessage('You must pipe a string to the python script');

        (new Bridge())
            ->setPython(getenv('PYTHON_BIN'))
            ->setScript(__DIR__ . '/_fixtures/ok.script.py')
            ->run();
    }

    /** @test */
    public function it_fails_when_script_does_not_exist()
    {
        $this->expectException(BridgeException::class);
        $this->expectExceptionMessageMatches('/.*No such file or directory*/');

        (new Bridge())
            ->setPython(getenv('PYTHON_BIN'))
            ->setScript(__DIR__ . '/_fixtures/does-not-exist.script.py')
            ->pipeRaw('{"name":"John Doe"}')
            ->run();
    }

    /** @test */
    public function it_fails_when_script_throws_error()
    {
        $this->expectException(BridgeException::class);
        $this->expectExceptionMessageMatches('/.*Something went wrong.*/');

        (new Bridge())
            ->setPython(getenv('PYTHON_BIN'))
            ->setScript(__DIR__ . '/_fixtures/error.script.py')
            ->pipeRaw('{"name":"John Doe"}')
            ->run();
    }
}
