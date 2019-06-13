<?php

namespace Noclue\GitHelper\Exception;

class ExecResult
{
    public $output;
    public $returnCode;

    public function getOutput() : array
    {
        return $this->output;
    }

    public function getReturnCode() : int
    {
        return $this->returnCode;
    }
}
