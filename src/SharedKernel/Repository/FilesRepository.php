<?php

namespace Hgraca\Phorensic\SharedKernel\Repository;

use Hgraca\Phorensic\SharedKernel\Port\Database\DatabaseClientInterface;
use Hgraca\Phorensic\SharedKernel\Port\FileSystem\Adapter\FileSystem\FileSystemAdapter;
use Hgraca\Phorensic\SharedKernel\Port\FileSystem\FileSystemInterface;

final class FilesRepository implements FilesRepositoryInterface
{
    const TBL_FILES = 'files';
    const TBL_FILES_COL_PATH = 'path';
    const TBL_FILES_COL_TYPE = 'type';
    const TBL_FILES_COL_COMMITS = 'commits';
    const TBL_FILES_COL_ACTIVE_DAYS = 'active_days';

    /**
     * @var DatabaseClientInterface
     */
    private $dbClient;

    /**
     * @var FileSystemInterface
     */
    private $fileSystem;

    public function __construct(DatabaseClientInterface $dbClient, FileSystemInterface $fileSystem = null)
    {
        $this->dbClient = $dbClient;
        $this->fileSystem = $fileSystem ?? new FileSystemAdapter();
    }

    public function storeFilesChangeRate(array $data)
    {
        $this->dbClient->create(self::TBL_FILES, $this->addColumnNames($data));
    }

    public function storePhpFilesMetrics(array $data)
    {
        foreach ($data as $filePath => $metrics) {
            $this->dbClient->update(self::TBL_FILES, $metrics, ['path' => $filePath]);
        }
    }

    public function findPhpFiles(): array
    {
        $fileList = $this->dbClient->read(self::TBL_FILES, [self::TBL_FILES_COL_TYPE => 'php'], [], null);

        return array_column($fileList, self::TBL_FILES_COL_PATH);
    }

    private function addColumnNames(array $data): array
    {
        $dataWithColumnNames = [];
        foreach ($data as $file) {
            $dataWithColumnNames[$file[0]][self::TBL_FILES_COL_PATH] = $file[0];
            $dataWithColumnNames[$file[0]][self::TBL_FILES_COL_TYPE] = $this->fileSystem->getExtension($file[0]);
            $dataWithColumnNames[$file[0]][self::TBL_FILES_COL_COMMITS] = $file[1];
            $dataWithColumnNames[$file[0]][self::TBL_FILES_COL_ACTIVE_DAYS] = $file[2];
        }

        return $dataWithColumnNames;
    }
}
