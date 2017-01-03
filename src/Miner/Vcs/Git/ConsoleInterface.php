<?php
namespace Hgraca\Phorensic\Miner\Vcs\Git;

interface ConsoleInterface
{
    public function gitEffort(string $repoPath, string $since = null): string;
}
