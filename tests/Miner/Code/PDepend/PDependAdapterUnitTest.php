<?php

namespace Hgraca\Phorensic\Test\Miner\Code\PDepend;

use Hgraca\Helper\InstanceHelper;
use Hgraca\Phorensic\Miner\Code\PDepend\PDependAdapter;
use Hgraca\Phorensic\SharedKernel\Port\FileSystem\FileSystemInterface;
use Mockery;
use Mockery\MockInterface;
use PDepend\Engine;
use PHPUnit_Framework_TestCase;

final class PDependAdapterUnitTest extends PHPUnit_Framework_TestCase
{
    /** @var PDependAdapter */
    private $adapter;

    /** @var MockInterface|FileSystemInterface */
    private $fileSystem;

    /** @var MockInterface|Engine */
    private $pdepend;

    /**
     * @before
     */
    public function setUpAdapter()
    {
        $this->fileSystem = Mockery::mock(FileSystemInterface::class);
        $this->pdepend = Mockery::mock(Engine::class);
        $this->adapter = new PDependAdapter($this->pdepend, $this->fileSystem);
    }

    /**
     * @test
     *
     * @small
     */
    public function mine()
    {
        $fileA = 'Y/Z/a.php';
        $fileB = 'Y/b.php';
        $fileC = 'Y/Z/c.php';
        $fileD = 'Y/Z/d.php';

        $this->fileSystem->shouldReceive('readFile')->once()->with('/X/' . $fileA)->andReturn('file that can be handled');
        $this->fileSystem->shouldReceive('readFile')->once()->with('/X/' . $fileB)->andReturn('file that can NOT be handled class(');
        $this->fileSystem->shouldReceive('readFile')->once()->with('/X/' . $fileC)->andReturn('file that can be handled');
        $this->fileSystem->shouldReceive('readFile')->once()->with('/X/' . $fileD)->andReturn('file that can be handled');

        $this->pdepend->shouldReceive('addFile')->once()->with('/X/' . $fileA);
        $this->pdepend->shouldNotReceive('addFile')->with('/X/' . $fileB);
        $this->pdepend->shouldReceive('addFile')->once()->with('/X/' . $fileC);
        $this->pdepend->shouldReceive('addFile')->once()->with('/X/' . $fileD);

        $this->pdepend->shouldReceive('addReportGenerator')->once()->with($this->adapter);
        $adapter = $this->adapter;
        $expectedMetrics = [
                $fileA => ['metricA' => 1, 'metricB' => 2],
                $fileC => ['metricA' => 3, 'metricB' => 4],
                $fileD => ['metricA' => 5, 'metricB' => 6],
            ];
        $this->pdepend->shouldReceive('analyze')->once()->andReturnUsing(
            function() use ($adapter, $expectedMetrics) {
                InstanceHelper::setProtectedProperty($adapter, 'sourceFileMetricsList', $expectedMetrics);
        });

        $actualMetrics = $this->adapter->mine(
            [
                $fileA,
                $fileB,
                $fileC,
                $fileD,
            ],
            '/X'
        );

        self::assertEquals($expectedMetrics, $actualMetrics);
    }
}
