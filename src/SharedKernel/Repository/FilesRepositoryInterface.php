<?php
namespace Hgraca\Phorensic\SharedKernel\Repository;

interface FilesRepositoryInterface
{
    public function storeFilesChangeRate(array $data);

    public function storePhpFilesMetrics(array $data);

    public function findPhpFiles(): array;
}
