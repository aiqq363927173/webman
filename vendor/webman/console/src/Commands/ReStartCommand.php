<?php

namespace Webman\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Webman\Console\Application;

class ReStartCommand extends Command
{
    protected static $defaultName = 'restart';
    protected static $defaultDescription = 'Restart workers. Use mode -d to start in DAEMON mode. Use mode -g to stop gracefully.';

    protected function configure() : void
    {
        $this
            ->addOption('daemon', 'd', InputOption::VALUE_NONE, 'DAEMON mode')
            ->addOption('graceful', 'g', InputOption::VALUE_NONE, 'graceful stop');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        Application::run();
        return self::SUCCESS;
    }
}
