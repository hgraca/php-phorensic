<?php

namespace Hgraca\Phorensic\Analyser;

use Hgraca\Phorensic\Analyser\Query\RefactorPriorityQuery;
use Hgraca\Phorensic\SharedKernel\Command\StorageAwareCommandAbstract;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class AnalyseCommand extends StorageAwareCommandAbstract
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('phorensic:analyse')
            ->setDescription('Analyse the data stored in a DB.')
            ->addArgument('dbPath', InputArgument::REQUIRED, 'What db do you want to analyse?')
            ->addArgument('limit', InputArgument::OPTIONAL, 'How many results do you want to see?', null);
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $refactorPriorityQuery = $this->getRefactorPriorityQuery($input);

        $data = $refactorPriorityQuery->execute($input->getArgument('limit'));

        foreach ($data as $dataRow) {
            $output->writeln(str_pad($dataRow['refactor_priority'], 5, ' ', STR_PAD_LEFT) . ' ' . $dataRow['path']);
        }
    }

    private function getRefactorPriorityQuery(InputInterface $input): RefactorPriorityQuery
    {
        return new RefactorPriorityQuery($this->getDatabaseClient($this->getDatabasePath($input)));
    }
}
