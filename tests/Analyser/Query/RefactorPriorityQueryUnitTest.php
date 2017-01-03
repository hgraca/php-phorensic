<?php

namespace Hgraca\Phorensic\Test\Analyser\Query;

use Hgraca\Phorensic\Analyser\Query\RefactorPriorityQuery;
use Hgraca\Phorensic\SharedKernel\Port\Database\DatabaseClientInterface;
use Mockery;
use PHPUnit_Framework_TestCase;

final class RefactorPriorityQueryUnitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     *
     * @small
     *
     * @dataProvider dataProviderFor_execute_should_limit_the_query_if_its_given
     */
    public function execute_should_limit_the_query_if_its_given(string $limit = null, string $expectedSQL)
    {
        $dbClientMock = Mockery::mock(DatabaseClientInterface::class);
        $dbClientMock->shouldReceive('executeQuery')->once()->with($expectedSQL);

        $refactorPriorityQuery = new RefactorPriorityQuery($dbClientMock);

        $refactorPriorityQuery->execute($limit);
    }

    public function dataProviderFor_execute_should_limit_the_query_if_its_given(): array
    {
        return [
            [$limit = 30, RefactorPriorityQuery::SQL . " LIMIT $limit"],
            [null, RefactorPriorityQuery::SQL],
        ];
    }
}
