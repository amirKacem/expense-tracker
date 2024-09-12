<?php

use App\Commands\CreateUserCommand;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Symfony\Component\Console\Application;

$container = require __DIR__ . '/../bootstrap.php';

$entityManager = $container->get(EntityManagerInterface::class);

$config = new PhpFile(CONFIG_PATH . '/migrations.php');
$dependcyFactory = $dependencyFactory = DependencyFactory::fromEntityManager(
    $config,
    new ExistingEntityManager($entityManager)
);
$migrationCommands = require CONFIG_PATH . '/commands/migration_commands.php';
$customCommands = require CONFIG_PATH . '/commands/commands.php';

$application = new Application('expennies', '1.0');
ConsoleRunner::addCommands($application, new SingleManagerProvider($entityManager));

$application->addCommands($migrationCommands($dependcyFactory));
$application->addCommands(array_map(fn ($command) => $container->get($command), $customCommands));

$application->run();
