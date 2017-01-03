<?php

namespace Hgraca\Phorensic\Miner\Code\PDepend;

use Hgraca\Helper\StringHelper;
use Hgraca\Phorensic\Miner\Code\CodeMinerInterface;
use Hgraca\Phorensic\SharedKernel\Port\FileSystem\Adapter\FileSystem\FileSystemAdapter;
use Hgraca\Phorensic\SharedKernel\Port\FileSystem\FileSystemInterface;
use PDepend\Application;
use PDepend\Engine;
use PDepend\Metrics\Analyzer;
use PDepend\Metrics\Analyzer\ClassLevelAnalyzer;
use PDepend\Report\CodeAwareGenerator;
use PDepend\Source\AST\AbstractASTClassOrInterface;
use PDepend\Source\AST\ASTArtifact;
use PDepend\Source\AST\ASTArtifactList;
use PDepend\Source\AST\ASTClass;
use PDepend\Source\AST\ASTInterface;
use PDepend\Source\ASTVisitor\AbstractASTVisitor;

final class PDependAdapter extends AbstractASTVisitor implements CodeAwareGenerator, CodeMinerInterface
{
    /** @var ClassLevelAnalyzer[] */
    private $analyzers;

    /** @var ASTArtifactList */
    private $artifacts;

    /** @var array ['filePath' => ['metricA' => value, 'metricB' => value, ...]] */
    private $sourceFileMetricsList = [];

    /** @var FileSystemInterface */
    private $fileSystem;

    /** @var Engine */
    private $pdepend;

    /** @var string[] */
    private static $unsupportedTokenList = ['class(', 'yield'];

    public function __construct(Engine $pdepend = null, FileSystemInterface $fileSystem = null)
    {
        $this->fileSystem = $fileSystem ?? new FileSystemAdapter();
        $this->pdepend = $pdepend ?? (new Application())->getEngine();
    }

    /**
     * @param string[] $filePathList
     */
    public function mine(array $filePathList, string $basePath = ''): array
    {
        $basePath = rtrim($basePath, '/') . '/';

        foreach ($filePathList as $filePath) {
            $filePath = $basePath . $filePath;
            if ($this->fileCanNotBeHandledByPdepend($filePath)) {
                continue;
            }
            $this->pdepend->addFile($filePath);
        }

        $this->pdepend->addReportGenerator($this);
        $this->pdepend->analyze();

        if ($basePath !== '') {
            $this->sourceFileMetricsList = $this->removeBasePathFromBeginningOfAllPaths(
                $basePath,
                $this->sourceFileMetricsList
            );
        }

        return $this->sourceFileMetricsList;
    }

    /**
     * Sets the context code nodes.
     *
     * @codeCoverageIgnore
     */
    public function setArtifacts(ASTArtifactList $artifacts)
    {
        $this->artifacts = $artifacts;
    }

    /**
     * Adds an analyzer to log. If this logger accepts the given analyzer it
     * with return <b>true</b>, otherwise the return value is <b>false</b>.
     *
     * @codeCoverageIgnore
     *
     * @return boolean
     */
    public function log(Analyzer $analyzer)
    {
        $this->analyzers[] = $analyzer;

        return true;
    }

    /**
     * Closes the logger process and writes the output file.
     *
     * @codeCoverageIgnore
     */
    public function close()
    {
        foreach ($this->artifacts as $node) {
            $node->accept($this);
        }
    }

    /**
     * Returns an <b>array</b> with accepted analyzer types. These types can be
     * concrete analyzer classes or one of the descriptive analyzer interfaces.
     *
     * @codeCoverageIgnore
     *
     * @return string[]
     */
    public function getAcceptedAnalyzers()
    {
        return [
            'pdepend.analyzer.cyclomatic_complexity',
            'pdepend.analyzer.node_loc',
            'pdepend.analyzer.npath_complexity',
            'pdepend.analyzer.inheritance',
            'pdepend.analyzer.node_count',
            'pdepend.analyzer.hierarchy',
            'pdepend.analyzer.crap_index',
            'pdepend.analyzer.code_rank',
            'pdepend.analyzer.coupling',
            'pdepend.analyzer.class_level',
            'pdepend.analyzer.cohesion',
        ];
    }

    /**
     * Visits a class node.
     *
     * @codeCoverageIgnore
     */
    public function visitClass(ASTClass $node)
    {
        $this->visitFile($node);

        parent::visitClass($node);
    }

    /**
     * Visits an interface node.
     *
     * @codeCoverageIgnore
     */
    public function visitInterface(ASTInterface $node)
    {
        $this->visitFile($node);

        parent::visitInterface($node);
    }

    /**
     * @codeCoverageIgnore
     */
    private function visitFile(AbstractASTClassOrInterface $node)
    {
        if (!$node->isUserDefined()) {
            return;
        }

        $this->sourceFileMetricsList[$node->getCompilationUnit()->getFileName()] = $this->collectMetrics($node);
    }

    /**
     * Collects the collected metrics for the given node and adds them to the <b>$node</b>.
     *
     * @codeCoverageIgnore
     */
    private function collectMetrics(ASTArtifact $node)
    {
        $metrics = [];

        foreach ($this->analyzers as $analyzer) {
            $metrics = array_merge($metrics, $analyzer->getNodeMetrics($node));
        }

        return $metrics;
    }

    /**
     * This method returns true if the given file contains a token that is not currently supported by pdepend
     */
    private function fileCanNotBeHandledByPdepend(string $filePath): bool
    {
        if (empty(self::$unsupportedTokenList)) {
            return false;
        }

        $fileContents = $this->fileSystem->readFile($filePath);
        foreach (self::$unsupportedTokenList as $unsupportedToken) {
            if (StringHelper::has($unsupportedToken, $fileContents)) {
                return true;
            }
        }

        return false;
    }

    private function removeBasePathFromBeginningOfAllPaths(string $basePath, array $sourceFileMetricsList)
    {
        $cleanedUpSourceFileMetricsList = [];
        foreach ($sourceFileMetricsList as $sourceFilePath => $sourceFileMetrics) {
            $cleanedUpSourceFilePath = StringHelper::removeFromBeginning($basePath, $sourceFilePath);
            $cleanedUpSourceFileMetricsList[$cleanedUpSourceFilePath] = $sourceFileMetrics;
        }
        return $cleanedUpSourceFileMetricsList;
    }
}
