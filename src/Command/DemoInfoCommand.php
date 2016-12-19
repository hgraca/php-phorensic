<?php

namespace Phorensic\Command;

use Cilex\Provider\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Example command for testing purposes.
 */
class DemoInfoCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('demo:info')
            ->setDescription('Get Application Information');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // This is a contrived example to show accessing services
        // from the container without needing the command itself
        // to extend from anything but Symfony Console's base Command.

        $app = $this->getApplication()->getService('console');

        $output->writeln('Name: ' . $app->getName());
        $output->writeln('Version: ' . $app->getVersion());
    }
}
