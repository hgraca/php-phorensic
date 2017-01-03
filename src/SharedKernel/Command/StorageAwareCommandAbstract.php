<?php

namespace Hgraca\Phorensic\SharedKernel\Command;

use Cilex\Provider\Console\Command;
use Hgraca\MicroDbal\Crud\CrudClient;
use Hgraca\MicroDbal\Crud\QueryBuilder\Sql\SqlQueryBuilder;
use Hgraca\MicroDbal\Raw\PdoClient;
use Hgraca\Phorensic\SharedKernel\Port\Database\Adapter\MicroDbal\MicroDbalAdapter;
use Hgraca\Phorensic\SharedKernel\Port\Database\DatabaseClientInterface;
use PDO;
use Symfony\Component\Console\Input\InputInterface;

abstract class StorageAwareCommandAbstract extends Command
{
    protected function getDatabaseClient($dbPath): DatabaseClientInterface
    {
        $dsn = 'sqlite:' . $dbPath;
        $crudClient = new CrudClient($rawClient = new PdoClient(new PDO($dsn)), new SqlQueryBuilder());

        return new MicroDbalAdapter($crudClient, $rawClient);
    }

    protected function getDefaultStorageFilePath(): string
    {
        $time = date("Y-m-d_H:i:s");

        return ROOT_DIR . "/var/analyse_$time.sqlite";
    }

    protected function getDatabasePath(InputInterface $input): string
    {
        return $input->getArgument('dbPath') ?? $this->getDefaultStorageFilePath();
    }
}
