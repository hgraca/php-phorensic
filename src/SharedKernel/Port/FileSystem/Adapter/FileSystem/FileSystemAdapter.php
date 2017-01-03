<?php

namespace Hgraca\Phorensic\SharedKernel\Port\FileSystem\Adapter\FileSystem;

use Hgraca\FileSystem\Exception\FileNotFoundException as FileSystemFileNotFoundException;
use Hgraca\FileSystem\Exception\InvalidPathException as FileSystemInvalidPathException;
use Hgraca\FileSystem\FileSystemInterface as FileSystemFileSystemInterface;
use Hgraca\FileSystem\LocalFileSystem;
use Hgraca\Phorensic\SharedKernel\Port\FileSystem\Exception\FileNotFoundException;
use Hgraca\Phorensic\SharedKernel\Port\FileSystem\Exception\InvalidPathException;
use Hgraca\Phorensic\SharedKernel\Port\FileSystem\FileSystemInterface;

final class FileSystemAdapter implements FileSystemInterface
{
    /**
     * @var FileSystemFileSystemInterface
     */
    private $fileSystem;

    public function __construct(FileSystemFileSystemInterface $fileSystem = null)
    {
        $this->fileSystem = $fileSystem ?? new LocalFileSystem(LocalFileSystem::IDEMPOTENT);
    }

    /**
     * @throws FileNotFoundException
     * @throws InvalidPathException
     */
    public function readFile(string $path): string
    {
        try {
            return $this->fileSystem->readFile($path);
        } catch (FileSystemInvalidPathException $e) {
            throw new InvalidPathException('', 0, $e);
        } catch (FileSystemFileNotFoundException $e) {
            throw new FileNotFoundException('', 0, $e);
        }
    }

    public function getExtension(string $path): string
    {
        return $this->fileSystem->getExtension($path);
    }
}
