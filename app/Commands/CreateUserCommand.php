<?php

declare(strict_types=1);

namespace App\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:create-user')]
class CreateUserCommand extends Command
{
    protected static $defaultName = 'My App';

    protected static $defaultDescription = 'My Command';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->write('Hello World', true);
        return Command::SUCCESS;
    }
}
