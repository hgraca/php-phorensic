<?php

namespace Hgraca\Phorensic\Miner\Vcs\Git;

use Hgraca\Phorensic\Miner\Vcs\VcsMinerInterface;

final class GitAdapter implements VcsMinerInterface
{
    /** @var ConsoleInterface */
    private $console;

    public function __construct(ConsoleInterface $console)
    {
        $this->console = $console;
    }

    public function findMostChangedFiles(string $repoPath, string $since = null): array
    {
        return $this->parseGitEffortOutput($this->console->gitEffort($repoPath, $since));
    }

    private function parseGitEffortOutput(string $gitEffortOutput)
    {
        $outputLines = explode(PHP_EOL, $gitEffortOutput);

        $foundFirstBlankLine = false;
        $relevantOutputLines = [];
        foreach ($outputLines as $line) {
            $foundSecondBlankLine = ($foundFirstBlankLine && $line === '') ? true : false;
            $foundFirstBlankLine = ($foundFirstBlankLine || $line === '') ? true : false;

            if ($foundSecondBlankLine) {
                break;
            }

            if (!$foundFirstBlankLine || $line === '') {
                continue;
            }

            $relevantLine = explode(' ', preg_replace('/\s+/', ' ', trim($line)));
            $relevantLine[0] = trim($relevantLine[0], ".");
            $relevantOutputLines[] = $relevantLine;
        }
        array_pop($relevantOutputLines);

        return $relevantOutputLines;
    }
}
