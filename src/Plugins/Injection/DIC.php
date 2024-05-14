<?php

declare(strict_types=1);

namespace App\Plugins\Injection;

use App\Service\IArtifactService;
use App\Service\IService;
use DI\Container;
use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use Monolog\Level;

class DIC
{
    public const string SERVICE_INITIAL_PATH = "App\\Service\\";
    public const string SERVICE_SUFFIX       = "Service";

    private static ?Container $container = null;

    /**
     * Return the DI container
     * @return Container The DI container
     * @throws Exception
     */
    public static function getContainer(): Container
    {
        if (!self::$container) {
            $builder = new ContainerBuilder();
            $builder->addDefinitions('config/container.php');
            self::$container = $builder->build();
        }

        return self::$container;
    }

    /**
     * Returns an instance of the service method requested.
     *
     * @param string $name
     *
     * @return IService
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    public static function getServiceByName(string $name): IService
    {
        //Service full path
        $servicePath = self::SERVICE_INITIAL_PATH . "{$name}\\{$name}" . self::SERVICE_SUFFIX;

        return self::getContainer()->get($servicePath);
    }

    /**
     * @param $name
     *
     * @return IArtifactService
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function getArtifactServiceByName($name): IArtifactService
    {
        $service = self::getServiceByName($name);

        if (!$service instanceof IArtifactService) {
            throw new NotFoundException();
        }

        return $service;
    }

    /**
     * Returns the monolog logging level based on the configuration
     * @return Level
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    public static function getLoggingLevel(): Level
    {
        return self::getContainer()->get('logging_level') === 1
            ? Level::Debug
            : Level::Error;
    }
}
