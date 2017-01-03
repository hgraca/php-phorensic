<?php

namespace Hgraca\Phorensic\Test\SharedKernel\Port\FileSystem\Adapter\FileSystem;

use Hgraca\FileSystem\Exception\FileNotFoundException;
use Hgraca\FileSystem\Exception\InvalidPathException;
use Hgraca\FileSystem\FileSystemInterface;
use Hgraca\Phorensic\SharedKernel\Port\FileSystem\Adapter\FileSystem\FileSystemAdapter;
use Mockery;
use Mockery\MockInterface;
use PHPUnit_Framework_TestCase;

final class FileSystemAdapterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var MockInterface|FileSystemInterface
     */
    private $fileSystemMock;

    /**
     * @var FileSystemAdapter
     */
    private $adapter;

    /**
     * @before
     */
    public function setUpAdapter()
    {
        $this->fileSystemMock = Mockery::mock(FileSystemInterface::class);
        $this->adapter = new FileSystemAdapter($this->fileSystemMock);
    }

    /**
     * @test
     *
     * @small
     */
    public function readFile()
    {
        $path = 'some/path/to/file';
        $this->fileSystemMock->shouldReceive('readFile')->once()->with($path)->andReturn('some content');

        $this->adapter->readFile($path);
    }

    /**
     * @test
     *
     * @small
     *
     * @expectedException \Hgraca\Phorensic\SharedKernel\Port\FileSystem\Exception\InvalidPathException
     */
    public function readFile_ThrowsExceptionIfInvalidPath()
    {
        $path = 'some/path/to/file';
        $this->fileSystemMock->shouldReceive('readFile')->once()->with($path)->andThrow(InvalidPathException::class);

        $this->adapter->readFile($path);
    }

    /**
     * @test
     *
     * @small
     *
     * @expectedException \Hgraca\Phorensic\SharedKernel\Port\FileSystem\Exception\FileNotFoundException
     */
    public function readFile_ThrowsExceptionIfPathNotFound()
    {
        $path = 'some/path/to/file';
        $this->fileSystemMock->shouldReceive('readFile')->once()->with($path)->andThrow(FileNotFoundException::class);

        $this->adapter->readFile($path);
    }

    /**
     * @dataProvider dataProvider_test_getExtension
     */
    public function test_getExtension(string $path, string $expectedExtension)
    {
        $adapter = new FileSystemAdapter();

        self::assertEquals($expectedExtension, $adapter->getExtension($path));
    }

    public function dataProvider_test_getExtension()
    {
        return [
            ['/a/dir/fileA', ''],
            ['/a/dir/fileA.php', 'php'],
            ['/a/dir/fileA.bladibla', 'bladibla'],
        ];
    }
}
