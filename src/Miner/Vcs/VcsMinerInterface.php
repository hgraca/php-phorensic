<?php

namespace Hgraca\Phorensic\Miner\Vcs;

interface VcsMinerInterface
{
    public function findMostChangedFiles(string $since): array;
}
