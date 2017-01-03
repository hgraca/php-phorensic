<?php

namespace Hgraca\Phorensic\Miner\Code;

interface CodeMinerInterface
{
    /**
     * @param string[] $filePathList
     */
    public function mine(array $filePathList): array;
}
