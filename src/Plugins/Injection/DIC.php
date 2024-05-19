<?php

declare(strict_types=1);

namespace App\Plugins\Injection;

use App\Service\IArtifactService;
use App\Service\IComponentService;
use App\Service\IService;
use DI\Container;
use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use League\Plates\Engine;
use Monolog\Level;

class DIC
{
    public const string SERVICE_INITIAL_PATH = "App\\Service\\";
    public const string SERVICE_SUFFIX       = "Service";

    private static ?Container $container = null;
    private static ?Engine $platesEngine = null;

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
     * @param string $category
     * @param string $name
     *
     * @return IService
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    public static function getServiceByName(string $category, string $name): IService
    {
        //Service full path
        $servicePath = self::SERVICE_INITIAL_PATH . "{$category}\\{$name}" . self::SERVICE_SUFFIX;

        return self::getContainer()->get($servicePath);
    }

    /**
     * @param string $name
     *
     * @return IArtifactService
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function getArtifactServiceByName(string $name): IArtifactService
    {
        $service = self::getServiceByName(
            category: $name,
            name: $name
        );

        if (!$service instanceof IArtifactService) {
            throw new NotFoundException();
        }

        return $service;
    }

    /**
     * @param string $category
     * @param string $name
     *
     * @return IComponentService
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function getComponentServiceByName(
        string $category,
        string $name
    ): IComponentService {
        $service = self::getServiceByName($category, $name);

        if (!$service instanceof IComponentService) {
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

    /**
     * Returns the singleton plates engine
     * @return Engine
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    public static function getPlatesEngine(): Engine
    {
        if (!self::$platesEngine) {
            self::$platesEngine = self::getContainer()->get(Engine::class);
        }

        return self::$platesEngine;
    }
}
