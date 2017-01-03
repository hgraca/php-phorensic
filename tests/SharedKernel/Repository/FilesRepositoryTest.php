<?php

namespace Hgraca\Phorensic\Test\SharedKernel\Repository;

use Hgraca\Phorensic\SharedKernel\Port\Database\DatabaseClientInterface;
use Hgraca\Phorensic\SharedKernel\Repository\FilesRepository;
use Mockery;
use Mockery\MockInterface;
use PHPUnit_Framework_TestCase;

final class FilesRepositoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var MockInterface|DatabaseClientInterface
     */
    private $databaseClientMock;

    /**
     * @var FilesRepository
     */
    private $repository;

    /**
     * @before
     */
    public function setUpAdapter()
    {
        $this->databaseClientMock = Mockery::mock(DatabaseClientInterface::class);
        $this->repository = new FilesRepository($this->databaseClientMock);
    }

    /**
     * @test
     *
     * @small
     */
    public function storeFilesChangeRate()
    {
        $data = [
            ['a/path/to/file.php', 'c', 'e'],
            ['some/path/to/file.txt', 'c', 'e'],
            ['another/path/to/file.bladibla', 'c', 'e'],
            ['another/path/to/file', 'c', 'e'],
        ];
        $expected = [
            'a/path/to/file.php' => [FilesRepository::TBL_FILES_COL_PATH => 'a/path/to/file.php', FilesRepository::TBL_FILES_COL_TYPE => 'php', FilesRepository::TBL_FILES_COL_COMMITS => 'c', FilesRepository::TBL_FILES_COL_ACTIVE_DAYS => 'e'],
            'some/path/to/file.txt' => [FilesRepository::TBL_FILES_COL_PATH => 'some/path/to/file.txt', FilesRepository::TBL_FILES_COL_TYPE => 'txt', FilesRepository::TBL_FILES_COL_COMMITS => 'c', FilesRepository::TBL_FILES_COL_ACTIVE_DAYS => 'e'],
            'another/path/to/file.bladibla' => [FilesRepository::TBL_FILES_COL_PATH => 'another/path/to/file.bladibla', FilesRepository::TBL_FILES_COL_TYPE => 'bladibla', FilesRepository::TBL_FILES_COL_COMMITS => 'c', FilesRepository::TBL_FILES_COL_ACTIVE_DAYS => 'e'],
            'another/path/to/file' => [FilesRepository::TBL_FILES_COL_PATH => 'another/path/to/file', FilesRepository::TBL_FILES_COL_TYPE => '', FilesRepository::TBL_FILES_COL_COMMITS => 'c', FilesRepository::TBL_FILES_COL_ACTIVE_DAYS => 'e'],
        ];
        $this->databaseClientMock->shouldReceive('create')
            ->once()
            ->with(FilesRepository::TBL_FILES, $expected);

        $this->repository->storeFilesChangeRate($data);
    }

    /**
     * @test
     *
     * @small
     */
    public function storePhpFilesMetrics()
    {
        $data = [
            'a' => ['b'],
            'c' => ['d'],
            'e' => ['f'],
        ];
        foreach ($data as $path => $criteria) {
            $this->databaseClientMock->shouldReceive('update')->once()->with(
                FilesRepository::TBL_FILES,
                $criteria,
                ['path' => $path]
            );
        }

        $this->repository->storePhpFilesMetrics($data);
    }

    /**
     * @test
     *
     * @small
     */
    public function findPhpFiles()
    {
        $data = [
            [FilesRepository::TBL_FILES_COL_PATH => 'a', FilesRepository::TBL_FILES_COL_TYPE => 'b',],
            [FilesRepository::TBL_FILES_COL_PATH => 'c', FilesRepository::TBL_FILES_COL_TYPE => 'd',],
            [FilesRepository::TBL_FILES_COL_PATH => 'e', FilesRepository::TBL_FILES_COL_TYPE => 'f',],
        ];
        $expectedResult = ['a', 'c', 'e'];

        $this->databaseClientMock->shouldReceive('read')
            ->once()
            ->with(
                FilesRepository::TBL_FILES,
                [FilesRepository::TBL_FILES_COL_TYPE => 'php'],
                [],
                null
            )
            ->andReturn($data);

        self::assertEquals($expectedResult, $this->repository->findPhpFiles());
    }
}
