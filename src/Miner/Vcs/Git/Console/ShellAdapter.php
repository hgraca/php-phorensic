<?php

namespace Hgraca\Phorensic\Miner\Vcs\Git\Console;

use Hgraca\Phorensic\Miner\Vcs\Git\ConsoleInterface;

final class ShellAdapter implements ConsoleInterface
{
    public function gitEffort(string $repoPath, string $since = null): string
    {
        $shellCommand = "cd $repoPath; git effort" . ($since ? " -- --since='$since'" : '');

        return `$shellCommand`;
    }
}
