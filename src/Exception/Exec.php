<?php

namespace Noclue\GitHelper\Exception;

class Exec
{
    private $command;
    private $result;

    public function __construct(string $command)
    {
        $this->command = $command;
        $this->result = new ExecResult();
    }

    public function exec() : ExecResult
    {
        exec($this->command, $this->result->output, $this->result->returnCode);

        return $this->result;
    }
}
