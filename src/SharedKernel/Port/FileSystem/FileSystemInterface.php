<?php

namespace Hgraca\Phorensic\SharedKernel\Port\FileSystem;

use Hgraca\Phorensic\SharedKernel\Port\FileSystem\Exception\FileNotFoundException;
use Hgraca\Phorensic\SharedKernel\Port\FileSystem\Exception\InvalidPathException;

interface FileSystemInterface
{
    /**
     * @throws FileNotFoundException
     * @throws InvalidPathException
     */
    public function readFile(string $path): string;

    public function getExtension(string $path): string;
}
