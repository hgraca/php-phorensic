<?php

namespace Hgraca\Phorensic\Test\SharedKernel\Port\Database\Adapter\MicroDbal;

use Hgraca\MicroDbal\CrudClientInterface;
use Hgraca\MicroDbal\RawClientInterface;
use Hgraca\Phorensic\SharedKernel\Port\Database\Adapter\MicroDbal\MicroDbalAdapter;
use Mockery;
use Mockery\MockInterface;
use PHPUnit_Framework_TestCase;

final class MicroDbalAdapterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var MockInterface|CrudClientInterface
     */
    private $crudClientMock;

    /**
     * @var MockInterface|RawClientInterface
     */
    private $rawClientMock;

    /**
     * @var MicroDbalAdapter
     */
    private $adapter;

    /**
     * @before
     */
    public function setUpAdapter()
    {
        $this->crudClientMock = Mockery::mock(CrudClientInterface::class);
        $this->rawClientMock = Mockery::mock(RawClientInterface::class);
        $this->adapter = new MicroDbalAdapter($this->crudClientMock, $this->rawClientMock);
    }

    /**
     * @test
     *
     * @small
     */
    public function executeQuery()
    {
        $query = 'some query';
        $bindingsList = ['a', 'B', 'C'];
        $this->rawClientMock->shouldReceive('executeQuery')
            ->once()
            ->with($query, $bindingsList)
            ->andReturn([]);

        $this->adapter->executeQuery($query, $bindingsList);
    }

    /**
     * @test
     *
     * @small
     */
    public function create()
    {
        $table = 'some table';
        $data = ['a', 'B', 'C'];
        $this->crudClientMock->shouldReceive('create')
            ->once()
            ->with($table, $data);

        $this->adapter->create($table, $data);
    }

    /**
     * @test
     *
     * @small
     */
    public function read()
    {
        $table = 'some table';
        $filter = ['C', 'D', 'E'];
        $orderBy = ['a', 'B', 'C'];
        $limit = 2;
        $offset = 3;
        $this->crudClientMock->shouldReceive('read')
            ->once()
            ->with($table, $filter, $orderBy, $limit, $offset);

        $this->adapter->read($table, $filter, $orderBy, $limit, $offset);
    }

    /**
     * @test
     *
     * @small
     */
    public function update()
    {
        $table = 'some table';
        $data = ['a', 'B', 'C'];
        $filter = ['C', 'D', 'E'];
        $this->crudClientMock->shouldReceive('update')
            ->once()
            ->with($table, $data, $filter);

        $this->adapter->update($table, $data, $filter);
    }
}
