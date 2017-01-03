<?php

namespace Hgraca\Phorensic\SharedKernel\Port\Database;

interface DatabaseClientInterface
{
    /**
     * Executes a query for data.
     *
     * @param string $queryString The native query string, with place holders for the data bindings
     * @param array $bindingsList The data bindings to be injected in the query, escaped and formatted in the correct
     * data type, according to the native data engine being used.
     * Ie: ['bindingName' => 'value', 'otherBindingName' => 1, ...]
     */
    public function executeQuery(string $queryString, array $bindingsList = []): array;

    public function create(string $table, array $data);

    public function read(string $table, array $filter = [], array $orderBy = [], int $limit = null, int $offset = 1): array;

    public function update(string $table, array $data, array $filter = []);
}
