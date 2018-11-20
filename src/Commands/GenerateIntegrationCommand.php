<?php

namespace DDGen\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateIntegrationCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('generate:integration')
            ->setDescription('Generate integration boilerplate for a given class.')
            ->addArgument('className', InputArgument::REQUIRED, 'Full-qualified class name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $className = $input->getArgument('className');
        $output->writeln('Generating integration boilerplate for <info>'.$className.'</info>...');
    }
}
