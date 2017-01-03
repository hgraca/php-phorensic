<?php

namespace Hgraca\Phorensic\SharedKernel\Port\Database\Adapter\MicroDbal;

use Hgraca\MicroDbal\CrudClientInterface;
use Hgraca\MicroDbal\RawClientInterface;
use Hgraca\Phorensic\SharedKernel\Port\Database\DatabaseClientInterface;

final class MicroDbalAdapter implements DatabaseClientInterface
{

    /**
     * @var CrudClientInterface
     */
    private $crudClient;

    /**
     * @var RawClientInterface
     */
    private $rawClient;

    public function __construct(CrudClientInterface $crudClient, RawClientInterface $rawClient)
    {
        $this->crudClient = $crudClient;
        $this->rawClient = $rawClient;
    }

    /**
     * Executes a query for data.
     *
     * @param string $queryString The native query string, with place holders for the data bindings
     * @param array $bindingsList The data bindings to be injected in the query, escaped and formatted in the correct
     * data type, according to the native data engine being used.
     * Ie: ['bindingName' => 'value', 'otherBindingName' => 1, ...]
     */
    public function executeQuery(string $queryString, array $bindingsList = []): array
    {
        return $this->rawClient->executeQuery($queryString, $bindingsList);
    }

    public function create(string $table, array $data)
    {
        $this->crudClient->create($table, $data);
    }

    public function read(string $table, array $filter = [], array $orderBy = [], int $limit = null, int $offset = 1): array
    {
        return $this->crudClient->read($table, $filter, $orderBy, $limit, $offset);
    }

    public function update(string $table, array $data, array $filter = [])
    {
        $this->crudClient->update($table, $data, $filter);
    }
}
