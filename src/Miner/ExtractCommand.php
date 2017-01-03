<?php

namespace Hgraca\Phorensic\Miner;

use Hgraca\Phorensic\Miner\Code\CodeMinerInterface;
use Hgraca\Phorensic\Miner\Code\PDepend\PDependAdapter;
use Hgraca\Phorensic\Miner\Vcs\Git\Console\ShellAdapter;
use Hgraca\Phorensic\Miner\Vcs\Git\GitAdapter;
use Hgraca\Phorensic\Miner\Vcs\VcsMinerInterface;
use Hgraca\Phorensic\SharedKernel\Command\StorageAwareCommandAbstract;
use Hgraca\Phorensic\SharedKernel\Repository\FilesRepository;
use Hgraca\Phorensic\SharedKernel\Repository\FilesRepositoryInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ExtractCommand extends StorageAwareCommandAbstract
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('phorensic:extract')
            ->setDescription('Extract, from a repository, all information possible and store the results in a DB.')
            ->addArgument('repositoryPath', InputArgument::OPTIONAL, 'What repository do you want to analyse?', getcwd())
            ->addArgument(
                'since',
                InputArgument::OPTIONAL,
                'Since when do you want to analyse? (ie: "2010-11-23", defaults to last 3 months)'
            )
            ->addArgument(
                'dbPath',
                InputArgument::OPTIONAL,
                'To where do you want to save the extracted data? (sqlite DB)'
            );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dbPath = $this->setUpDatabase($input);
        $storage = $this->getStorageService($dbPath);

        $this->extractFileChangeRates($input, $output, $storage);
        $this->extractPhpFilesMetrics($input, $output, $storage);

        $output->writeln('Extracted data has been stored in: ' . $dbPath);
    }

    private function extractFileChangeRates(InputInterface $input, OutputInterface $output, FilesRepositoryInterface $storage)
    {
        $output->writeln('Extracting file change rates...');
        $storage->storeFilesChangeRate(
            $this->getVcsMiner()->findMostChangedFiles($input->getArgument('repositoryPath'), 'since')
        );
        $output->writeln('Finished extracting file change rates.');
    }

    private function extractPhpFilesMetrics(InputInterface $input, OutputInterface $output, FilesRepositoryInterface $storage)
    {
        $output->writeln('Extracting PHP files metrics...');
        $phpFilesList = $storage->findPhpFiles();
        $analysis = $this->getCodeMiner()->mine($phpFilesList, $input->getArgument('repositoryPath'));
        $storage->storePhpFilesMetrics($analysis);
        $output->writeln('Finished extracting PHP files metrics.');
    }

    private function setUpDatabase(InputInterface $input): string
    {
        $dbPath = $this->getDatabasePath($input);
        copy(ROOT_DIR . "/storage/template.sqlite", $dbPath);

        return $dbPath;
    }

    private function getVcsMiner(): VcsMinerInterface
    {
        return new GitAdapter(new ShellAdapter());
    }

    private function getCodeMiner(): CodeMinerInterface
    {
        return new PDependAdapter();
    }

    private function getStorageService(string $dbPath): FilesRepositoryInterface
    {
        return new FilesRepository($this->getDatabaseClient($dbPath));
    }
}
