<?php

declare(strict_types=1);

use App\Auth\Auth;
use App\Auth\UserProvider;
use App\Config;
use App\Contracts\AuthInterface;
use App\Contracts\SessionInterface;
use App\Contracts\UserProviderInterface;
use App\DTO\SessionConfig;
use App\Enum\AppEnvironment;
use App\Enum\SameSite;
use App\Auth\Session\Session;
use App\Contracts\RequestValidatorFatcoryInterface;
use App\Factory\RequestValidatorFactory;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\App;
use Slim\Csrf\Guard;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Asset\VersionStrategy\JsonManifestVersionStrategy;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookup;
use Symfony\WebpackEncoreBundle\Asset\TagRenderer;
use Symfony\WebpackEncoreBundle\Twig\EntryFilesTwigExtension;
use Twig\Extra\Intl\IntlExtension;

use function DI\create;

return [
    App::class => function (ContainerInterface $container) {

        $routeFiles = array_diff(scandir(CONFIG_PATH.'/routes'), array('.', '..'));

        $addMiddlewares = require CONFIG_PATH . '/middlewares.php';

        AppFactory::setContainer($container);
        $app = AppFactory::create();
        foreach($routeFiles as $filePath) {
            $router = require CONFIG_PATH . '/routes/'.$filePath;
            $router($app);
        }

        $addMiddlewares($app);
        return $app;
    },
    Config::class => create(Config::class)->constructor(require CONFIG_PATH . '/app.php'),
    EntityManagerInterface::class => function (Config $config) {
        $configSetup = ORMSetup::createAttributeMetadataConfiguration(
            paths: $config->get('doctrine.entity_dir'),
            isDevMode: $config->get('doctrine.dev_mode'),
        );
        $connection = DriverManager::getConnection(
            $config->get('doctrine.connection'),
            $configSetup
        );
        return new EntityManager($connection, $configSetup);
    },
    Twig::class => function (Config $config, ContainerInterface $container) {
        $twig = Twig::create(VIEW_PATH, [
            'cache' => STORAGE_PATH . '/cache/templates',
            'auto_reload' => AppEnvironment::isDevelopment($config->get('app_environment')),
        ]);

        $twig->addExtension(new IntlExtension());
        $twig->addExtension(new EntryFilesTwigExtension($container));
        $twig->addExtension(new AssetExtension($container->get('webpack_encore.packages')));

        return $twig;
    },
    'webpack_encore.packages' => fn () =>
        new Packages(
            new Package(new JsonManifestVersionStrategy(BUILD_PATH . '/manifest.json'))
        )
    ,
    'webpack_encore.tag_renderer' => fn (ContainerInterface $container) => new TagRenderer(
        new EntrypointLookup(BUILD_PATH . "/entrypoints.json"),
        $container->get('webpack_encore.packages')
    ),
    ResponseFactoryInterface::class => fn (App $app) => $app->getResponseFactory(),
    AuthInterface::class => fn (ContainerInterface $container) => $container->get(Auth::class),
    UserProviderInterface::class => fn (ContainerInterface $container) => $container->get(UserProvider::class),
    SessionInterface::class => fn (Config $config) => new Session(
        new SessionConfig(
            $config->get('session.name', ''),
            $config->get('session.flash_key', 'flash'),
            $config->get('session.secure', true),
            $config->get('session.httponly', true),
            SameSite::from($config->get('session.samesite', 'lax'))
        )
    ),
    RequestValidatorFatcoryInterface::class => fn (ContainerInterface $container) =>
        new RequestValidatorFactory($container)
    ,
    'csrf'  => fn (ResponseFactoryInterface $responseFactory) => new Guard(
        $responseFactory,
        persistentTokenMode: true
    ),

];
