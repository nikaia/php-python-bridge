<?php

declare(strict_types=1);

namespace Nikaia\PythonBridge;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Bridge
{
    /** @var string Full path to python script file */
    protected string $script;

    /** @var string piped string */
    protected string $pipedString;

    public function __construct(
        /** @var string python executable, you can specify the full path if in a custom location */
        protected string $pythonPath = 'python'
    )
    {
    }

    public static function create(): self
    {
        return new self();
    }

    /**
     * @param string $pythonPath
     * @return Bridge
     */
    public function setPython(string $pythonPath): Bridge
    {
        $this->pythonPath = $pythonPath;

        return $this;
    }

    /**
     * Set the python script that the runner will execute.
     */
    public function setScript(string $script): self
    {
        $this->script = $script;

        return $this;
    }

    /**
     * Set the piped string that will be sent to the python script.
     */
    public function setPipedString(string $pipedString): self
    {
        $this->pipedString = $pipedString;

        return $this;
    }

    /**
     * Echo the passed string and pipe it to the python script
     * i.e : echo '...string...' | python script.js.
     */
    public function pipeRaw(string $string): self
    {
        $escaped = str_replace("'", "\'", $string);

        return $this->setPipedString("echo '" . $escaped . "'");
    }

    /**
     * Pipe the passed array to the python script after json encoding it.
     */
    public function pipe(array $input): self
    {
        return $this->pipeRaw(json_encode($input));
    }

    /**
     * Run the python script.
     */
    public function run(): Response
    {
        if (empty($this->pipedString)) {
            throw new BridgeException('You must pipe a string to the python script');
        }

        $this->setUtf8Context();

        $command = "{$this->pipedString} | {$this->pythonPath} " . $this->script;

        $process = Process::fromShellCommandline($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new BridgeException(
                (new ProcessFailedException($process))->getMessage()
            );
        }

        return new Response($process->getOutput());
    }

    /**
     * Fix issues where when python get executed by
     * the php process it will defaults to ascii and tries to
     * read strings in ascii, and mess everythings up.
     *
     * @see https://stackoverflow.com/a/13969829/146253
     */
    protected function setUtf8Context(): void
    {
        setlocale(LC_ALL, $locale = 'en_US.UTF-8');
        putenv('LC_ALL=' . $locale);
    }
}
