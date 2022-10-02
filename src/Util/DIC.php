<?php

declare(strict_types=1);

namespace App\Util;

use DI\Container;
use DI\ContainerBuilder;

class DIC {

    /**
     * Return the DI container
     * @return Container The DI container
     */
    public static function getContainer(): Container {
        $builder = new ContainerBuilder();
        $builder->addDefinitions('config/container.php');
        $container = $builder->build();
        return $container;
    }
}
