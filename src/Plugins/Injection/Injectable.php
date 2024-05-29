<?php

namespace App\Plugins\Injection;

use DI\Container;
use Exception;

/**
 * Utility class that can provide the dependency injection container as property.
 */
abstract class Injectable
{
    /**
     * DI container
     * @var Container
     */
    protected Container $container;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->container = DIC::getContainer();
    }
}