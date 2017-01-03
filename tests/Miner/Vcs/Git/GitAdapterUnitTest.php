<?php

namespace Hgraca\Phorensic\Test\Miner\Vcs\Git;

use Hgraca\Phorensic\Miner\Vcs\Git\Console\ShellAdapter;
use Hgraca\Phorensic\Miner\Vcs\Git\ConsoleInterface;
use Hgraca\Phorensic\Miner\Vcs\Git\GitAdapter;
use Mockery;
use Mockery\MockInterface;
use PHPUnit_Framework_TestCase;

final class GitAdapterUnitTest extends PHPUnit_Framework_TestCase
{
    /** @var GitAdapter */
    private $adapter;

    /** @var string */
    private $repoPath;

    /** @var MockInterface|ShellAdapter */
    private $shell;

    /**
     * @before
     */
    public function setUpAdapter()
    {
        $this->repoPath = 'a/dummy/path';
        $this->shell = Mockery::mock(ConsoleInterface::class);
        $this->adapter = new GitAdapter($this->shell);
    }

    /**
     * @test
     *
     * @small
     */
    public function findMostChangedFiles()
    {
        $since = 'last month';
        $this->shell->shouldReceive('gitEffort')
            ->once()
            ->with($this->repoPath, $since)
            ->andReturn(file_get_contents(__DIR__ . '/GitAdapterUnitTest.findMostChangedFiles.in.sh'));

        $expected = include __DIR__ . '/GitAdapterUnitTest.findMostChangedFiles.out.php';

        self::assertEquals(
            $expected,
            $this->adapter->findMostChangedFiles($this->repoPath, $since)
        );
    }
}
