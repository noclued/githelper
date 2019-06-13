<?php
declare(strict_types=1);
namespace Noclue\GitHelper;

use function chdir;
use Noclue\GitHelper\Exception\Exec;
use Noclue\GitHelper\Exception\RunTimeException;
use Noclue\GitHelper\Exception\UnexpectedOutput;

class Helper
{
    private $repoPath;

    public function __construct(string $repoPath = '.')
    {
        $this->repoPath = $repoPath;
        chdir($repoPath);
        $this->checkGit();
    }

    public function getFileNamesChangedSince(int $commits = 1) : array
    {
        $command = sprintf("git diff --name-only HEAD~%d HEAD", $commits);
        $exec = new Exec($command);
        $result = $exec->exec();

        if ($result->getReturnCode() === 0) {
            return $result->getOutput();
        }

        throw new UnexpectedOutput(join("\n", $result->getOutput()));
    }

    public function getLineAddedToFileSince(string $fileName, int $commits = 1) : array
    {
        $pattern = '/^\{\+(.+)?\+\}$/';

        return $this->getLinesByPatternFromFileSince($fileName, $pattern, $commits);
    }

    public function getLinesRemovedFromFileSince(string $fileName, int $commits = 1) : array
    {
        $pattern = '/^\[\-(.+)?\-\]$/';

        return $this->getLinesByPatternFromFileSince($fileName, $pattern, $commits);
    }

    protected function getLinesByPatternFromFileSince(string $fileName, string $pattern, int $commits) : array
    {
        $command = sprintf("git diff HEAD~%d HEAD -U0 --word-diff %s", $commits, $fileName);
        $exec = new Exec($command);
        $lines = $exec->exec()->getOutput();
        $retValue = [];

        foreach ($lines as $line) {
            preg_match($pattern, $line, $matches);
            if (count($matches) == 2) {
                $retValue[] = $matches[1];
            }
        }

        return $retValue;
    }


    private function checkGit() : void
    {
        $command = "git";
        $exec = new Exec($command);
        $result = $exec->exec();
        if ($result->getReturnCode() !== 0 && $result->getReturnCode() !== 1) {
            throw new RunTimeException('Git not found on host!');
        }

        $command = "git log --name-status HEAD^..HEAD";
        $exec = new Exec($command);
        $result = $exec->exec();
        if ($result->getReturnCode() !== 0) {
            throw new RunTimeException('Given path is not a git directory');
        }
    }
}
